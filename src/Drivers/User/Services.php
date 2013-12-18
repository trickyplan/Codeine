<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        foreach($Call['Services'] as $Service)
        {
            if (isset($Call['Session']['User'][$Service]['Auth'])
            && !empty($Call['Session']['User'][$Service]['Auth']))
            {
                $Call['Output']['Content'][] =
                    [
                        'Type'  => 'Block',
                        'Class' => 'alert alert-success',
                        'Value' => '<a href="/user/disconnect/'.$Service.'"><l>User.Services:'.$Service.'.Connected</l>'
                    ];
            }
            else
                $Call['Output']['Content'][] =
                    [
                        'Type'  => 'Block',
                        'Class' => 'alert alert-warning',
                        'Value' => '<a href="/user/connect/'.$Service.'"><l>User.Services:'.$Service.'.Disconnected</l></a>'
                    ];
        }
        return $Call;
    });