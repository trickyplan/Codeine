$(document).ready(function ()
    {
        $('.ajax-interval').each(
            function ()
            {
                var el = $(this);
                Visibility.every(el.attr('data-interval'), function(){
                    $.ajax({
                        type: 'GET',
                        url: el.attr('data-url'),
                        success: function(data)
                        {
                            $(el).html(data)
                        }
                    });
                });
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

        $(document).on ('ajax-load', '.ajax-delayed', function (event)
            {
                var el = $(this);
                if (el.attr('loaded') == true)
                    ;
                else
                {
                    $.ajax({
                        type: 'GET',
                        url: el.attr('data-url'),
                        success: function(data)
                        {
                            el.html(data)
                            el.attr('loaded', true);
                        }
                    });
                }
                event.stopPropagation();
                return true;
            });
        return true;
    }
);