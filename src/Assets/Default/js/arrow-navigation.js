$(function()
{
    $(document).keydown(function() {
        switch (event.keyCode ? event.keyCode : event.which ? event.which : null)
        {
            case 0x25:
                $('a[rel="prev"]').click();
            break;
            case 0x27:
                $('a[rel="next"]').click();
            break;
        }
    });
});