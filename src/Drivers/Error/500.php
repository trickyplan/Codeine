<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Page', function ($Call)
    {
        $Call['Headers']['HTTP/1.0'] = '500 Internal Server Error';

        $Call['Title'] = '500';
        $Call['Description'] = 'TODO';
        $Call['Keywords'] = array ('TODO');

        $Call['Output']['Content'] = array (array (
                                                'Type'  => 'Template',
                                                'Scope' => 'Error',
                                                'Value' => '500',
                                                'Data' => array(

                                                )
                                            ));
        return $Call;
     });

    self::setFn('Die', function ($Call)
    {
        // TODO Realize "Die" function
        die('500');

        return $Call;
    });