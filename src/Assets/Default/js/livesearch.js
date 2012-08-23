$(function()
{
    var oldData = $('#payload').html();

    $('#livesearch').keyup(function()
    {
        el = $('#livesearch');

        if (el.val().length > 3)
        {
            $.ajax(
                {
                    url: el.attr('data-search'),
                    type: 'post',
                    data:
                    {
                        Query: el.val()
                    },
                    success: function (data)
                    {
                        $('#payload').html(data);
                    }
                }
            )
        }
        else
            $('#payload').html(oldData);

        return true;
    });
});