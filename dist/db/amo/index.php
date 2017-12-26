<?php 



use \AmoCRM\Handler;
use \AmoCRM\Request;
use \AmoCRM\Lead;
use \AmoCRM\Contact;
use \AmoCRM\Note;
use \AmoCRM\Task;

require('vendor/autoload.php');

/* Предположим, пользователь ввел какие-то данные в форму на сайте */
/*$name = 'test testov';
$phone = '79161111111';
$email = 'user@user.com';
$message = 'Здравствуйте';
$utm_source = 'utm_source ';
$utm_medium = 'utm_medium ';
$utm_campaign = 'utm_campaign ';
$utm_content = 'utm_content ';
$utm_term= 'utm_term';
$product= 'utm_term';*/



/* Оборачиваем в try{} catch(){}, чтобы отлавливать исключения */
try {
	$api = new Handler('rezart', 'mcpovar@gmail.com');


	/* Создаем сделку,
	$api->config содержит в себе массив конфига,
	который вы создавали в начале */
	$lead = new Lead();
	$lead
		/* Название сделки */
		->setName('Заявка '.$data['orderType']) 
		/* Назначаем ответственного менеджера */
		->setResponsibleUserId($api->config['ResponsibleUserId'])
		/* Кастомное поле */
		->setCustomField(
			$api->config['page_url'], // ID поля
			$data['page_url'] // ID значения поля
		)
		->setCustomField(
			$api->config['city'], // ID поля
			$data['city'] // ID значения поля
		)
		->setCustomField(
			$api->config['date_visited'], // ID поля
			$data['date_visited'] // ID значения поля
		)
		->setCustomField(
			$api->config['time_visited'], // ID поля
			$data['time_visited'] // ID значения поля
		)
		->setCustomField(
			$api->config['ref'], // ID поля
			$data['ref'] // ID значения поля
		)
		->setCustomField(
			$api->config['utm_source'], // ID поля
			$data['utm_source'] // ID значения поля
		)
		->setCustomField(
			$api->config['utm_campaign'], // ID поля
			$data['utm_campaign'] // ID значения поля
		)
		->setCustomField(
			$api->config['utm_medium'], // ID поля
			$data['utm_medium'] // ID значения поля
		)
		->setCustomField(
			$api->config['utm_term'], // ID поля
			$data['utm_term'] // ID значения поля
		)
		->setCustomField(
			$api->config['utm_content'], // ID поля
			$data['utm_content'] // ID значения поля
		)
		->setCustomField(
			$api->config['form_subject'], // ID поля 
			$data['form_subject'] // ID значения поля
		)
		->setCustomField(
			$api->config['ga_id'], // ID поля 
			$data['click_id'] // ID значения поля
		)
		/* Теги. Строка - если один тег, массив - если несколько */
		->setTags(['#'.$data['orderType'], '#'.$data['page_url']])
		/* Статус сделки */
		->setStatusId($api->config['LeadStatusId']);

	/* Отправляем данные в AmoCRM
	В случае успешного добавления в результате
	будет объект новой сделки */
	$api->request(new Request(Request::SET, $lead));

	/* Сохраняем ID новой сделки для использования в дальнейшем */
	$lead = $api->last_insert_id;


	/* Создаем контакт */
	$contact = new Contact();
	$contact
		/* Имя */
		->setName($data['name'])
		/* Назначаем ответственного менеджера */
		->setResponsibleUserId($api->config['ResponsibleUserId'])
		/* Привязка созданной сделки к контакту */
		->setLinkedLeadsId($lead)
		/* Кастомные поля */
		->setCustomField(
			$api->config['ContactFieldPhone'],
			$data['phone'], // Номер телефона
			'MOB' // MOB - это ENUM для этого поля, список доступных значений смотрите в информации об аккаунте
		) 
		->setCustomField(
			$api->config['ContactFieldEmail'],
			$data['email'], // Email
			'WORK' // WORK - это ENUM для этого поля, список доступных значений смотрите в информации об аккаунте
		) 
		/* Теги. Строка - если один тег, массив - если несколько */
		->setTags(['Заявка', $data["orderType"].'-'.$data["page_url"]]);

	/* Проверяем по емейлу, есть ли пользователь в нашей базе */
	$api->request(new Request(Request::GET, ['query' => $data['email']], ['contacts', 'list']));
/*
	echo "<pre>";
	print_r($api);*/

	/* Если пользователя нет, вернется false, если есть - объект пользователя */
	$contact_exists = ($api->result) ? $api->result->contacts[0] : false;

	/* Если такой пользователь уже есть - мержим поля */
	if ($contact_exists) {
		$contact
			/* Указываем, что пользователь будет обновлен */
			->setUpdate($contact_exists->id, $contact_exists->last_modified + 1)
			/* Ответственного менеджера оставляем кто был */
			->setResponsibleUserId($contact_exists->responsible_user_id)
			/* Старые привязанные сделки тоже сохраняем */
			->setLinkedLeadsId($contact_exists->linked_leads_id);
	}


	/* Создаем заметку с сообщением из формы */
	// $note = new Note();
	// $note
	// 	/* Привязка к созданной сделке*/
	// 	->setElementId($lead)
	// 	/* Тип привязки (к сделке или к контакту). Смотрите комментарии в Note.php */
	// 	->setElementType(Note::TYPE_LEAD)
	// 	/* Тип заметки (здесь - обычная текстовая). Смотрите комментарии в Note.php */
	// 	->setNoteType(Note::COMMON)
	// 	/* Текст заметки*/
	// 	->setText($data['comment']);



	/* Создаем задачу для менеджера обработать заявку */
	$task = new Task();
	$task
		/* Привязка к созданной сделке */
		->setElementId($lead)
		/* Тип привязки (к сделке или к контакту) Смотрите комментарии в Task.php */
		->setElementType(Task::TYPE_LEAD)
		/* Тип задачи. Смотрите комментарии в Task.php */
		->setTaskType(Task::CALL)
		/* ID ответственного за задачу менеджера */
		->setResponsibleUserId($api->config['ResponsibleUserId'])
		/* Дедлайн задачи */
		->setCompleteTill(time() + 60 * 60)
		/* Текст задачи */
		->setText('Обработать заявку');


	/* Отправляем все в AmoCRM */
	$api->request(new Request(Request::SET, $contact));
	// $api->request(new Request(Request::SET, $note));
	$api->request(new Request(Request::SET, $task));
	

} catch (\Exception $e) {
	echo $e->getMessage();
}


?>