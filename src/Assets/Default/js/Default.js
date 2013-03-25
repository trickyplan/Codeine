$(document).ready(function()
{
    $(document).bind('keypress', '~', function() {
        $('table.console').toggle();
    });
});