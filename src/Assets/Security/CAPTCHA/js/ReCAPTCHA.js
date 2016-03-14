var CaptchaCallback = function()
{
    $('.g-recaptcha').each(function (index, el) {
        grecaptcha.render(el, {sitekey: $(el).attr('data-sitekey')});
    });
}