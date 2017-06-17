$(document).ready(function ()
    {
        $('.ajax-interval').each(
            function ()
            {
                var el = $(this);
                Visibility.every(el.attr('data-interval'), function(){
                    el.addClass('codeine-ajax-interval-loading');
                        $.ajax({
                            type: 'GET',
                            url: el.attr('data-url'),
                            success: function(data)
                            {
                                $(el).html(data)
                                el.removeClass('codeine-ajax-interval-loading');
                            }
                        });
                });
            }
        );

        $('.ajax').each(
            function ()
            {
                var el = $(this);
                el.addClass('codeine-ajax-loading');
                $.ajax({
                    type: 'GET',
                    url: el.attr('data-url'),
                    success: function(data)
                    {
                        $(el).html(data)
                        el.removeClass('codeine-ajax-loading');
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
                    el.addClass('codeine-ajax-delayed-loading');
                    $.ajax({
                        type: 'GET',
                        url: el.attr('data-url'),
                        success: function(data)
                        {
                            el.html(data)
                            el.attr('loaded', true);
                            el.removeClass('codeine-ajax-delayed-loading');
                        }
                    });
                }
                event.stopPropagation();
                return true;
            });
        return true;
    }
);