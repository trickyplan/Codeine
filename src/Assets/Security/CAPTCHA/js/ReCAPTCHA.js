grecaptcha.ready(function()
{
    $('.g-recaptcha').each(function (index, el) {
        grecaptcha.execute($(el).attr('data-sitekey'), {action: $(el).attr('data-action')}).then(function (token) {
            document.getElementById('g-recaptcha-response').value = token;
        });
    });
});