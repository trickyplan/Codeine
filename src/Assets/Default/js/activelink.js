// show current menu object highlighted
var url = decodeURI(window.location.pathname+window.location.search);

console.info(url);
// now grab every link from the navigation
$('.nav a').each(function () {
        // and test its href against the url pathname
        console.info(url);
        console.info($(this).attr('href'));
        if (($(this).attr('href') != '/') && (url == $(this).attr('href')))
        {
            console.log('Active link');
            $(this).addClass('active');
            $(this).parent().addClass('active');
            return false;
        }
});