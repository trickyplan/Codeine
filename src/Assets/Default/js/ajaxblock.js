/* global $, window */

$(document).ready(function () {
    $('.ajax').each(
        function () {
            let el = $(this);
            el.addClass('codeine-ajax-loading');
            $.ajax({
                type: 'GET',
                url: el.attr('data-url'),
                success: function (data) {
                    $(el).html(data);
                    el.removeClass('codeine-ajax-loading');
                }
            });
        }
    );

    $(document).on('ajax-load', '.ajax-delayed', function (event) {
        let el = $(this);
        if (el.attr('loaded') !== true) {
            el.addClass('codeine-ajax-delayed-loading');
            $.ajax({
                type: 'GET',
                url: el.attr('data-url'),
                success: function (data) {
                    el.html(data);
                    el.attr('loaded', true);
                    el.removeClass('codeine-ajax-delayed-loading');
                }
            });
        }
        event.stopPropagation();
        return true;
    });
    return true;
});
