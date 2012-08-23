<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn ('Page', function ($Call)
    {
        $Call['Headers']['HTTP/1.0'] = '403 Forbidden';

        $Call['Title'] = '403';
        $Call['Description'] = 'TODO';
        $Call['Keywords'] = array ('TODO');

        $Call['Output']['Content'] = array (array (
                                                'Type'  => 'Template',
                                                'Scope' => 'Errors',
                                                'ID' => '403',
                                                'Data'  => $Call
                                            ));


        unset ($Call['Service'], $Call['Method']);
        return $Call;
     });

    self::setFn('Block', function ($Call)
    {
        $Call['Output']['Content'] = array (array (
                                            'Type'  => 'Template',
                                            'Scope' => 'Errors/Blocks',
                                            'ID' => '403',
                                            'Data'  => $Call
                                        ));
        return $Call;
    });