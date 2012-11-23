$(document).ready(function ()
    {
        $('a.ajax-link').live('click',
            function ()
            {
                var el = $(this);
                $.ajax({
                    type: 'POST',
                    url: el.attr('href'),
                    success: function(data)
                    {
                        $('#'+el.attr('target')).html(data)
                    }

                });

                return false;
            }

        )
    }
)