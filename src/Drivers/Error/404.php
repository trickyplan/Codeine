<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Page', function ($Call)
    {
        $Call['Headers']['HTTP/1.0'] = '404 Not Found';
        $Call['Value'] = array();

        $Call['Title'] = '404';
        $Call['Description'] = 'TODO';
        $Call['Keywords'] = array ('TODO');


        $Call['Value'][] =
            array(
                'Place' => 'Content',
                'Type'  => 'Static',
                'Value' => 'Error/404'
            );

         return $Call;
     });