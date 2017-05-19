function filter(event){
  var tagName = (event.target || event.srcElement).tagName;
  return !(tagName == 'INPUT' || tagName == 'SELECT' || tagName == 'TEXTAREA' || tagName == 'IFRAME' || tagName == 'DIV' );
}