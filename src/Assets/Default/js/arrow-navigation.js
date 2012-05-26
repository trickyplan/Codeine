$(function()
{
    $(document).keydown(function() {
        switch (event.keyCode ? event.keyCode : event.which ? event.which : null)
        {
            case 0x25:
                link = $('a[rel="prev"]').attr('href');
            break;
            case 0x27:
                link = $('a[rel="next"]').attr('href')
            break;
        }
        if (link) document.location = link;
    });
});