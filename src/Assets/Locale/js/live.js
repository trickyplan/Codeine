$(document).ready(function(){
    $('.nl').on('click', function()
    {
        var translation = prompt('Перевести как');

        if (translation)
        {
            el = $(this);

            $.ajax(
            {
                    url: '/translate',
                    type: 'post',
                    data:
                    {
                        Token: el.html(),
                        Translation: translation
                    },
                    success: function (data)
                    {
                        el.html(translation);
                    }
                }
            );

            return false;
        }

    });
});