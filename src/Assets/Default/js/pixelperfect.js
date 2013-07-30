window.pixelperfect = false;

$(document).keydown(function(event)
{
    if (event.which == 66 && event.ctrlKey)
    {
        window.pixelperfect = !window.pixelperfect;

        if (window.pixelperfect)
            $('body').append('<div id="pixelperfect" style="' +
                'background-image: url(\'/img/pp'+window.location.pathname+'_layout.png\'); ' +
                'background-size: auto;' +
                'background-position: top center;' +
                'background-repeat: no-repeat;' +
                'width: 100%; ' +
                'height: 100%; ' +
                'position: absolute; ' +
                'top: 0; ' +
                'left: 0; ' +
                'z-index: 99;">' +
                '</div>');
        else
            $('#pixelperfect').remove();
    }
});