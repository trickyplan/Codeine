$(document).ready(function ()
{
    var str = location.href.toLowerCase();
    $("li a").each(function ()
    {
        if (str == this.href.toLowerCase())
            $(this).parent().addClass("active");
    });

    $("a").each(function(){
       if (str == this.href.toLowerCase())
           $(this).addClass("active");
    });
});