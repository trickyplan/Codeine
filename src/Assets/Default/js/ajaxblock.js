$(document).ready(function ()
    {
        $('.ajax-interval').each(
            function ()
            {
                var el = $(this);
                var interval = setInterval(function(){
                    $.ajax({
                        type: 'GET',
                        url: el.attr('data-url'),
                        success: function(data)
                        {
                            $(el).html(data)
                        }
                    });
                }, el.attr('data-interval'));
            }
        );

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
            }
        );
    }
);