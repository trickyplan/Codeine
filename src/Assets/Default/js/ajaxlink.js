$(document).ready(function ()
    {
        $('a.ajax').bind('click',
            function ()
            {
                var el = $(this);
                $('#'+el.attr('data-target')).addClass('text-muted');
                $.ajax({
                    type: 'GET',
                    url: el.attr('href'),
                    success: function(data)
                    {
                        $('#'+el.attr('data-target')).html(data)
                        $('#'+el.attr('data-target')).removeClass('text-muted');
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