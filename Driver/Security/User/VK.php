<?php

function F_VK_Step1 ($Args)
{
   View::JSFile('JS/OpenAPI.js');
   View::JS("window.vkAsyncInit = function() {
 VK.init({
         apiId: 1898867,
         nameTransportPath: '/xd_receiver.html'
        });
        VK.UI.button('vk_login');
      };
      (function() {
        var el = document.createElement('script');
        el.type = 'text/javascript';
        el.src = 'http://vkontakte.ru/js/api/openapi.js';
		el.charset=\"windows-1251\";
        el.async = true;
        document.getElementById('vk_api_transport').appendChild(el);
      }());
      window.vkAsyncInit();");
   View::Add('<div id="vk_api_transport"></div>
       <div onclick="doLogin()" id="vk_login"></div>');
}

function F_VK_Step2 ($Args)
{
    
}