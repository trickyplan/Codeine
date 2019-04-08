window.CaptchaCallback = function()
{
    $('.g-recaptcha').each(function (index, el) {
        grecaptcha.execute($(el).attr('data-sitekey'), {action: 'login'}).then(function (token) {
            $('<input type="hidden" />').attr({name: 'g-recaptcha-response', value: token}).appendTo(el);
        });
    });
}