key('ctrl + left', function ()
{
    window.location.replace($('a[rel="prev"]').attr('href'));
    return false;
});

key('ctrl + right', function ()
{
    window.location.replace($('a[rel="next"]').attr('href'));
    return false;
});