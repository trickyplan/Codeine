$(document).ready(function ()
    {
        $('a.ajax').live('click',
            function ()
            {
                var el = $(this);
                $('#'+el.attr('target')).addClass('muted');
                $.ajax({
                    type: 'POST',
                    url: el.attr('href'),
                    success: function(data)
                    {
                        $('#'+el.attr('target')).html(data)
                        $('#'+el.attr('target')).removeClass('muted');
                    }

                });

                return false;
            }

        )
    }
);