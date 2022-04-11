/* global key */
key.filter = function (event) {
    let tagName = event.target.tagName;
    return !(tagName === 'INPUT' || tagName === 'SELECT' || tagName === 'TEXTAREA' || tagName === 'IFRAME' || tagName === 'DIV');
};
