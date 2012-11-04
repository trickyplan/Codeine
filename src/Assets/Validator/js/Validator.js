console.info('Validator Loaded');

Codeine.Validator = function (el)
{
    $(el).submit(function(el)
        {
            $.ajax(
                {
                    url: $(this).attr('action'),
                    type: 'post',
                    data: $(this).serializeArray()
                }
            );

            return false;
        });
}