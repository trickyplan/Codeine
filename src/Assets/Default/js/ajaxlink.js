$(document).ready(function ()
    {
        $('a.ajax').bind('click',
            function ()
            {
                var el = $(this);
                $('#'+el.attr('target')).addClass('text-muted');
                $.ajax({
                    type: 'POST',
                    url: el.attr('href'),
                    success: function(data)
                    {
                        $('#'+el.attr('target')).html(data)
                        $('#'+el.attr('target')).removeClass('text-muted');
                    }

                });

                return false;
            }

        )
    }
);

$(document).ready(function ()
    {
        $('a[target=new]').bind('click',
            function ()
            {
                var el = $(this);

                window.open(el.attr('href'), 'new',"height=400,width=600");
                return false;
            }

        )
    }
);