$(document).ready(function ()
    {
        $('div.ajax, ul.ajax, span.ajax').each(
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