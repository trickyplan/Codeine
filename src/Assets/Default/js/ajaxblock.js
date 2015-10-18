$(document).ready(function ()
    {
        $('.ajax').each(
            function ()
            {
                var el = $(this);
                $.ajax({
                    type: 'POST',
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