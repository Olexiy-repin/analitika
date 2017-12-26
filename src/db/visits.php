<?php

  $user_agent   =  $_SERVER['HTTP_USER_AGENT'];

  function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    if (!empty($_GET['ip'])) { // це для себе щоб тестувать
      $ipaddress = $_GET['ip'];
    }

    return $ipaddress;
  }
  if (isset($_COOKIE["__utmz_gtm"])) {
    $cookie_utmz = $_COOKIE["__utmz_gtm"];
    list($utmcsr, $utmccn, $utmcmd) = split('[|]', $cookie_utmz);

    $utmcsr = split('[=]', $utmcsr);
    $utmcsr = $utmcsr[1];

    $utmccn = split('[=]', $utmccn);
    $utmccn = $utmccn[1];

    $utmcmd = split('[=]', $utmcmd);
    $utmcmd = $utmcmd[1];
  }



  $aff_sub = isset($_GET['aff_sub']) ? $_GET['aff_sub'] : null;
  $aff_id = isset($_GET['aff_id']) ? $_GET['aff_id'] : null;

  if (empty($aff_sub)) {
    $aff_sub = isset($_COOKIE['aff_sub']) ? $_COOKIE['aff_sub'] : null;
  }

  if (empty($aff_id)) {
    $aff_id = isset($_COOKIE['aff_id']) ? $_COOKIE['aff_id'] : null;
  }

  if (!empty($aff_sub) && !empty($aff_id) ) {
    SetCookie("aff_sub", $aff_sub, time()+2592000);
    SetCookie("aff_id", $aff_id, time()+2592000);
  }

  $data = array(
    'date_visited' => date("d.m.Y"),
    'time_visited' => date("G:i:s"),
    'page_url'     => 'http://funnel.rezart.agency/check/',
    'utm_source'   => isset($_GET['utm_source']) ? $_GET['utm_source'] : null,
    'utm_campaign' => isset($_GET['utm_campaign']) ? $_GET['utm_campaign'] : null,
    'utm_medium'   => isset($_GET['utm_medium']) ? $_GET['utm_medium'] : null,
    'utm_term'     => isset($_GET['utm_term']) ? $_GET['utm_term'] : null,
    'utm_content'  => isset($_GET['utm_content']) ? $_GET['utm_content'] : null,
    'ref'          => isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null,
    'ip_address'   => get_client_ip(),
    'city'         => isset($_GET['city']) ? $_GET['city'] : null,
    'client_id'    => isset($_COOKIE["_ga"]) ? substr($_COOKIE["_ga"], 6) : null,
    'utmcsr'       => isset($utmcsr) ? $utmcsr : null,
    'utmccn'       => isset($utmccn) ? $utmccn : null,
    'utmcmd'       => isset($utmcmd) ? $utmcmd : null,
    'affiliate_id' => isset($aff_id) ? $aff_id : null,
    'click_id'     => isset($aff_sub) ? $aff_sub : null
  );


// Параметры для подключения
/*$db_host = "localhost";
$db_user = "root"; // Логин БД
$db_password = "z"; // Пароль БД
$database = "allinsol_reg"; // БД*/
 $db_host = "womenc00.mysql.ukraine.com.ua";
$db_user = "womenc00_ra"; // Логин БД
$db_password = "lps8ba6t"; // Пароль БД
$database = "womenc00_ra"; // БД
// Подключение к базе данных
$db = mysql_connect($db_host,$db_user,$db_password) or die("Не могу создать соединение ");

// Выборка базы
mysql_select_db($database, $db);

mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET 'utf8'");
mysql_query("SET SESSION collation_connection = 'utf8_general_ci'");

// Построение SQL-оператора
  $query = "INSERT INTO
            `visits`(
                      `date_visited`,
                      `time_visited`,
                      `page_url`,
                      `utm_source`,
                      `utm_campaign`,
                      `utm_medium`,
                      `utm_term`,
                      `utm_content`,
                      `ref`,
                      `ip_address`,
                      `city`,
                      `client_id`,
                      `utmcsr`,
                      `utmccn`,
                      `utmcmd`,
                      `affiliate_id`,
                      `click_id`
                    )
            VALUES('".$data['date_visited']."',
                    '".$data['time_visited']."',
                    '".$data['page_url']."',
                    '".$data['utm_source']."',
                    '".$data['utm_campaign']."',
                    '".$data['utm_medium']."',
                    '".$data['utm_term']."',
                    '".$data['utm_content']."',
                    '".$data['ref']."',
                    '".$data['ip_address']."',
                    '".$data['city']."',
                    '".$data['client_id']."',
                    '".$data['utmcsr']."',
                    '".$data['utmccn']."',
                    '".$data['utmcmd']."',
                    '".$data['affiliate_id']."',
                    '".$data['click_id']."')";
// SQL-оператор выполняется
mysql_query($query) or die (mysql_error());
// Закрытие соединения
mysql_close();