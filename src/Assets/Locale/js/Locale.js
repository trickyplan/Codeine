$('.nl').on('click', function()
{
    var translation = prompt('Перевести как');

    if (translation)
    {
        el = $(this);

        $.ajax(
        {
                url: '/'+el.attr('lang')+'/dev/translate.json',
                type: 'post',
                data:
                {
                    Token: el.html(),
                    Translation: translation
                },
                success: function (data)
                {
                    if (data == true)
                    {
                        el.html(translation);
                        el.removeClass('nl');
                    }
                    else
                        el.html('Localization failed');
                }
            }
        );
    }
        return false;

});