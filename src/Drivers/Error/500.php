<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Page', function ($Call)
    {
        $Call['Headers']['HTTP/1.0'] = '500 Internal Server Error';

        $Call['Title'] = '500';
        $Call['Description'] = 'TODO';
        $Call['Keywords'] = array ('TODO');

        $Call['Output']['Content'] = array (array (
                                                'Type'  => 'Template',
                                                'Scope' => 'Errors',
                                                'Value' => '500',
                                                'Data' => array(

                                                )
                                            ));
        return $Call;
     });

    setFn('Die', function ($Call)
    {
        // TODO Realize "Die" function

        header('HTTP/1.0 500 Internal Server Error');
        if (file_exists('down.html'))
            readfile('down.html');
        else
            readfile('Codeine/down.html');
    });