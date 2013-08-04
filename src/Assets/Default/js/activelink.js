$(document).ready(function ()
{
    var str = location.href.toLowerCase();

    $("li a, td a, a").each(function ()
    {
        if (str.indexOf(this.href.toLowerCase())>-1)
            $(this).parent().addClass("active");
    });
});