const find = selector => document.querySelector(selector);
const findAll = selector => document.querySelectorAll(selector);

/* slider */

const Slider = (() => {
  let slideIndex = 1;

  const renderSlider = () => {
    const slides = document.getElementsByClassName('mySlides');

    if (slideIndex > slides.length) {
      slideIndex = 1;
    }

    if (slideIndex < 1) {
      slideIndex = slides.length;
    }

    Array.from(slides).forEach((slide) => {
      slide.style.display = 'none';
    });

    slides[slideIndex - 1].style.display = 'block';
  };

  const switchSlide = (offset) => {
    renderSlider(slideIndex += offset);
  };

  return {
    render: renderSlider, 
    switch: switchSlide,
  };
})();

Slider.render();

/* modal windows */

const Modal = (root) => {
  const show = () => {
    root.style.display = 'block';
    document.body.style.overflow = 'hidden';
  };

  const hide = () => {
    root.style.display = 'none';
    document.body.style.overflow = 'auto';
  };

  const init = () => {
    show(root);

    root.querySelector('.close')
      .onclick = () => hide(root);
    
    window.onclick = ({ target }) => {
      if (target === root) {
        hide(root);
      }
    };
  };

  return { show, hide, init };
};

const modalTriggers = findAll('.footer__link');
Array.from(modalTriggers).forEach((trigger) => {
  trigger.addEventListener('click', ({ target }) => {
    const element = find(target.dataset.modal);

    Modal(element).init();
  });
});

// form
// $(document).ready(function() {
//     function getUrlVars() {
//         var vars = {};
//         var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
//             vars[key] = value;
//         });
//         return vars;
//     }
//     $('input[name="utm_source"]').val(getUrlVars()["utm_source"]);
//     $('input[name="utm_campaign"]').val(getUrlVars()["utm_campaign"]);
//     $('input[name="utm_medium"]').val(getUrlVars()["utm_medium"]);
//     $('input[name="utm_term"]').val(getUrlVars()["utm_term"]);
//     $('input[name="utm_content"]').val(getUrlVars()["utm_content"]);
//     $('input[name="click_id"]').val(getUrlVars()["aff_sub"]);
//     $('input[name="affiliate_id"]').val(getUrlVars()["aff_id"]);
//     // $('input[name="page_url"]').val(window.location.hostname);
//     $('input[name="ref"]').val(document.referrer);
//
//     $.get("https://ipinfo.io", function(response) {
//         $('input[name="ip_address"]').val(response.ip);
//         $('input[name="city"]').val(response.city);
//         if (response.city != 'Kiev') {
//             console.log(response.city);
//         }
//     }, "jsonp");
//
//     var url = window.location.search;
//     setTimeout(function() {
//         $.ajax({
//             type: 'POST',
//             url: 'db/visits.php' + url,
//             dataType: 'json',
//             success: function(response) {
//                 console.info(response);
//                 if (response.status == 'success') {
//
//                 }
//             }
//         });
//     }, 5000);
//
//     function readCookie(name) {
//         var n = name + "=";
//         var cookie = document.cookie.split(';');
//         for (var i = 0; i < cookie.length; i++) {
//             var c = cookie[i];
//             while (c.charAt(0) == ' ') {
//                 c = c.substring(1, c.length);
//             }
//             if (c.indexOf(n) == 0) {
//                 return c.substring(n.length, c.length);
//             }
//         }
//         return null;
//     }
//     setTimeout(function() {
//         $('.gclid_field').val(readCookie('gclid'));
//     }, 3000);
//
//     /*db/registration.php*/
//     $('.registration').on('submit', function(e) {
//         e.preventDefault();
//         $('.submit').addClass('inactive');
//         $('.submit').prop('disabled', true);
//         var $form = $(this);
//         var $data = $form.find('input');
//
//
//
//         $.ajax({
//             type: 'POST',
//             url: 'db/registration.php',
//             dataType: 'json',
//             data: $form.serialize(),
//             success: function(response) {
//                 console.info(response);
//                 if (response.status == 'success') {
//                     window.location.href = "/success.html";
//                 }
//             }
//         });
//
//     });
//     /* form valid*/
//     var alertImage = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 286.1 286.1"><path d="M143 0C64 0 0 64 0 143c0 79 64 143 143 143 79 0 143-64 143-143C286.1 64 222 0 143 0zM143 259.2c-64.2 0-116.2-52-116.2-116.2S78.8 26.8 143 26.8s116.2 52 116.2 116.2S207.2 259.2 143 259.2zM143 62.7c-10.2 0-18 5.3-18 14v79.2c0 8.6 7.8 14 18 14 10 0 18-5.6 18-14V76.7C161 68.3 153 62.7 143 62.7zM143 187.7c-9.8 0-17.9 8-17.9 17.9 0 9.8 8 17.8 17.9 17.8s17.8-8 17.8-17.8C160.9 195.7 152.9 187.7 143 187.7z" fill="#E2574C"/></svg>';
//     var error;
//     $('.submit').click(function(e) {
//         e.preventDefault();
//         var ref = $(this).closest('form').find('[required]');
//         $(ref).each(function() {
//             if ($(this).val() == '') {
//                 var errorfield = $(this);
//                 if ($(this).attr("type") == 'email') {
//                       var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
//                        if (!pattern.test($(this).val())) {
//                         $("input[name=email]").val('');
//                         $(this).addClass('error').parent('.field').append('<div class="allert"><p>Укажите коректный e-mail</p>' + alertImage + '</div>');
//                         error = 1;
//                         $(":input.error:first").focus();
//                     }
//                 }else if($(this).attr("type") == 'tel'){
//                     var patterntel = /^()[- +()0-9]{9,18}/i;
//                     if (!patterntel.test($(this).val())) {
//                         $("input[name=phone]").val('');
//                         $(this).addClass('error').parent('.field').append('<div class="allert"><p>Укажите номер телефона в формате +3809999999</p>' + alertImage + '</div>');
//                         error = 1;
//                         $(":input.error:first").focus();
//                     }
//                 }else{
//                     $(this).addClass('error').parent('.field').append('<div class="allert"><p>Заполните это поле</p>' + alertImage + '</div>');
//                     error = 1;
//                     $(":input.error:first").focus();
//                 }
//                 return;
//             } else {
//                 error = 0;
//                 $(this).addClass('error').parent('.field').find('.allert').remove();
//             }
//         });
//         if (error !== 1) {
//             $(this).unbind('submit').submit();
//         }
//     });
//     /*end form valid*/
// });
