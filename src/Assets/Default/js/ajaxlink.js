$(document).ready(function ()
    {
        $('a.ajax').live('click',
            function ()
            {
                var el = $(this);
                $.ajax({
                    type: 'POST',
                    async: false,
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
);