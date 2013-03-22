<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Developer'] = F::loadOptions('Developer');

        $Call['License'] = json_decode(F::Run('IO', 'Read', ['Storage' => 'Web', 'Where' => $Call['Developer']['URL'].'/licenses/'.$Call['Project']['License']])[0], true);

        if (is_array($Call['License']))
            foreach ($Call['License'] as $Product => $License)
            {
                $Call['Output']['Licenses'][] =
                    [
                        'Type' => 'Block',
                        'Value' => '<h2>'.$Product.'</h2>'
                    ];

                $Call['Output']['Licenses'][] =
                    [
                        'Type' => 'Block',
                        'Class' => $License['Expire']>time()? 'alert alert-success': 'alert alert-danger',
                        'Value' => 'Действует до: <strong><datetime>'.$License['Expire'].'</datetime></strong>'
                    ];
            }
        else
            $Call['Output']['Licenses'][] =
                    [
                        'Type' => 'Block',
                        'Class' => 'alert alert-danger',
                        'Value' => 'Лицензий нет.'
                    ];

        return $Call;
    });