$(document).ready(function ()
    {
        $('.ajax').each(
            function ()
            {
                var el = $(this);
                $.ajax({
                    type: 'GET',
                    url: el.attr('data-url'),
                    success: function(data)
                    {
                        $(el).html(data)
                    }

                });

                return false;
            }

        )
    }
);