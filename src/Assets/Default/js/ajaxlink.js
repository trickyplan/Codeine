/* global $, window */

$(document).ready(function () {
    $('a.ajax').bind('click', function () {
        let el = $(this);
        $('#' + el.attr('data-target')).addClass('text-muted');
        $.ajax({
            type: 'GET', url: el.attr('href'), success: function (data) {
                let jl = $('#' + el.attr('data-target'));
                jl.html(data).removeClass('text-muted');
            }
        });
        return false;
    });
});

$(document).ready(function () {
    $('a[target=new]').bind('click', function () {
        let el = $(this);
        window.open(el.attr('href'), 'new', "height=400,width=600");
        return false;
    });
});
