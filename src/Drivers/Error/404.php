<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Page', function ($Call)
    {

        $Call['Headers']['HTTP/1.0'] = '404 Not Found';
        $Call['Output'] = array();

        $Call['Title'] = '404';
        $Call['Description'] = 'TODO';
        $Call['Keywords'] = array ('TODO');

        $Call['Output']['Content'] = array (array (
                                                'Type'  => 'Static',
                                                'Value' => 'Errors/404'
                                            ));

        return $Call;
     });