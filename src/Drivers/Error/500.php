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

        $Call['Output']['Content'] = [[
                                        'Type'  => 'Template',
                                        'Scope' => 'Errors',
                                        'Value' => '500',
                                        'Data' => []
                                    ]];
        return $Call;
     });

    setFn('Die', function ($Call)
    {
        die(str_replace('<place>Message</place>', $Call['On'], file_get_contents(F::findFile('Assets/Errors/500.html'))));
    });