// slider
// var slideIndex = 1;
// showDivs(slideIndex);
//
// function plusDivs(n) {
//   showDivs(slideIndex += n);
// }
//
// function showDivs(n) {
//   var i;
//   var x = document.getElementsByClassName("mySlides");
//   if (n > x.length) {slideIndex = 1}
//   if (n < 1) {slideIndex = x.length}
//   for (i = 0; i < x.length; i++) {
//      x[i].style.display = "none";
//   }
//   x[slideIndex-1].style.display = "block";
// }

// form
$(document).ready(function() {
    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
            vars[key] = value;
        });
        return vars;
    }
    $('input[name="utm_source"]').val(getUrlVars()["utm_source"]);
    $('input[name="utm_campaign"]').val(getUrlVars()["utm_campaign"]);
    $('input[name="utm_medium"]').val(getUrlVars()["utm_medium"]);
    $('input[name="utm_term"]').val(getUrlVars()["utm_term"]);
    $('input[name="utm_content"]').val(getUrlVars()["utm_content"]);
    $('input[name="click_id"]').val(getUrlVars()["aff_sub"]);
    $('input[name="affiliate_id"]').val(getUrlVars()["aff_id"]);
    // $('input[name="page_url"]').val(window.location.hostname);
    $('input[name="ref"]').val(document.referrer);

    $.get("https://ipinfo.io", function(response) {
        $('input[name="ip_address"]').val(response.ip);
        $('input[name="city"]').val(response.city);
        if (response.city != 'Kiev') {
            console.log(response.city);
        }
    }, "jsonp");

    var url = window.location.search;
    setTimeout(function() {
        $.ajax({
            type: 'POST',
            url: 'db/visits.php' + url,
            dataType: 'json',
            success: function(response) {
                console.info(response);
                if (response.status == 'success') {

                }
            }
        });
    }, 5000);

    function readCookie(name) {
        var n = name + "=";
        var cookie = document.cookie.split(';');
        for (var i = 0; i < cookie.length; i++) {
            var c = cookie[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1, c.length);
            }
            if (c.indexOf(n) == 0) {
                return c.substring(n.length, c.length);
            }
        }
        return null;
    }
    setTimeout(function() {
        $('.gclid_field').val(readCookie('gclid'));
    }, 3000);

    /*db/registration.php*/
    $('.registration').on('submit', function(e) {
        e.preventDefault();
        $('.submit').addClass('inactive');
        $('.submit').prop('disabled', true);
        var $form = $(this);
        var $data = $form.find('input');



        $.ajax({
            type: 'POST',
            url: 'db/registration.php',
            dataType: 'json',
            data: $form.serialize(),
            success: function(response) {
                console.info(response);
                if (response.status == 'success') {
                    window.location.href = "https://olexiy-repin.github.io/analitika/dist/success.html";
                }
            }
        });

    });
});
