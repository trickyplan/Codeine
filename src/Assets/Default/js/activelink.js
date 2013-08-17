$(document).ready(function ()
{
    var str = location.href.toLowerCase();

    $("li a, td a").each(function ()
    {
        if (this.href.toLowerCase() == str)
            $(this).parent().addClass("active");
    });
});