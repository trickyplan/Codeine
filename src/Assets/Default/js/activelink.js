// show current menu object highlighted
var url = decodeURI(window.location.pathname+window.location.search);
// now grab every link from the navigation
$('.nav a, ul a').each(function () {
        // and test its href against the url pathname
        if (($(this).attr('href') != '/') && (url == $(this).attr('href')))
        {
            $(this).addClass('active');
            $(this).parent().addClass('active');
            return false;
        }
});