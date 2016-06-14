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
        );

        $('div.ajax-interval, ul.ajax-interval, span.ajax-interval').each(
            function ()
            {
                var el = $(this);
                setInterval(function(){
                    $.ajax({
                        type: 'GET',
                        url: el.attr('data-url'),
                        success: function(data)
                        {
                            $(el).html(data)
                        }
                    });
                }, el.attr('data-interval'));


                return false;
            }
        )
    }
);