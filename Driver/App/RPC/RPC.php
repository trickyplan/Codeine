<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: RPC Test
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 3:54
     */

    self::Fn('RPC', function ($Call)
    {
        $Request = simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA']);

        echo '<?xml version="1.0"?>
 <methodResponse>
   <params>
     <param>
         <value><string>'.print_r($Request->params->param, true).'</string></value>
     </param>
   </params>
 </methodResponse>
';die();
    });