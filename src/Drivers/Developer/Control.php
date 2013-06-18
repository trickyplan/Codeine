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

        if (isset($Call['Project']['License']))
            $Call['License'] = json_decode(F::Run('IO', 'Read', ['Storage' => 'Web', 'Where' => $Call['Developer']['URL'].'/licenses/'
    .$Call['Project']['License']])[0], true); // FIXME

        if (isset($Call['License']) && is_array($Call['License']))
            foreach ($Call['License'] as $Product => $License)
            {
                $Call['Output']['Licenses'][] =
                    [
                        'Type' => 'Block',
                        'Value' => '<h3>'.$Product.'</h3>'
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