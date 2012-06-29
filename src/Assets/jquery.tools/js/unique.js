$.tools.validator.fn("[data-unique]", "Занято", function(input) {
    var result;
    $.ajax(
            {
                url: '/check',
                type: 'post',
                async: false,
                data:
                {
                    Key: input.attr('data-unique'),
                    Value: input.attr('value')
                },
                success: function (data)
                {
                    result = data ? true : ['data-unique'];
                }
            }
        );

    if((input.attr('value') != '') && input.attr('value') === input.attr('data-value'))
        result = true;

    return result;
});