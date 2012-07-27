function populate(entity)
{
    $.ajax(
        {
            url: '/populate',
            type: 'post',
            data:
            {
                Entity: entity
            },
            success: function (data)
            {
                for (key in data.Content[0])
                    $('[name='+key+']').val(data.Content[0][key])
            }
        }
    )
}