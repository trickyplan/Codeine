$(function () {
    // show current menu object highlighted
    var url = decodeURI(window.location.pathname);
    var checkString;

    // now grab every link from the navigation
    $('.nav a').each(function () {
        // and test its href against the url pathname
        checkString = url.match($(this).attr('href'));
        if ((checkString != null && $(this).attr('href') != '/') || (url == $(this).attr('href'))) {
            {
                $(this).addClass('active');
                $(this).parent().addClass('active');
                return false;
            }
        }
    });
});