<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Page', function ($Call)
    {
        $Call['HTTP']['Headers']['HTTP/1.1'] = '451 Unavailable For Legal Reasons';

        $Call['Page']['Title'] = '451';
        $Call['Page']['Description'] = 'TODO';
        $Call['Page']['Keywords'] = array ('TODO');

        $Call['Output']['Content'] = [[
                                        'Type'  => 'Template',
                                        'Scope' => 'Errors',
                                        'ID' => '451',
                                        'Data' => []
                                    ]];
        return $Call;
     });

    setFn('Die', function ($Call)
    {
        die(str_replace('<place>Message</place>', $Call['On'], file_get_contents(F::findFile('Assets/Errors/451.html'))));
    });