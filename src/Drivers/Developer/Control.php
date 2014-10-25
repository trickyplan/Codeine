<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Developer',
            'ID' => 'Overview'
        ];

        $Call['Developer'] = F::loadOptions('Developer');

        if (isset($Call['Developer']['URL']))
        {
            $Developer = F::Run('IO', 'Read', ['Storage' => 'Web','Where' => ['ID' => $Call['Developer']['URL']]]);

            if (isset($Developer[0]))
                $Call['Developer'] = jd($Developer[0], true);
        }

        if (isset($Call['Project']['License']))
            $Call['License'] = jd(F::Run('IO', 'Read', ['Storage' => 'Web', 'Where' => $Call['Developer']['URL'].'/licenses/'
    .$Call['Project']['License']])[0], true); // FIXME

        if (isset($Call['License']) && is_array($Call['License']))
            foreach ($Call['License'] as $Product => $License)
            {
                $Call['Output']['Licenses'][] =
                    [
                        'Type' => 'Heading',
                        'Level' => 3,
                        'Value' => $Product
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
                        'Value' => 'Поддержка не оказывается.'
                    ];

        return $Call;
    });