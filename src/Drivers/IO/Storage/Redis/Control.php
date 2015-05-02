<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'][] =
            [
                'Type' => 'Table',
                'Value' =>
                [
                    ['DB Size', F::Run('IO', 'Execute',
                                    [
                                        'Storage' => 'Redis',
                                        'Execute' => 'DBSize'
                                    ])]
                ]
            ];

        return $Call;
    });

    setFn('Status', function ($Call)
    {
        $Data = F::Run('IO', 'Execute',
            [
                'Storage' => 'Redis',
                'Execute' => 'Status'
            ]);
        if (null === $Data)
            $Output = [['Status', 'Offline']];
        else
        {
            $Output = [['Status', 'Online']];
            foreach ($Data as $Key => $Value)
                if (in_array($Key, $Call['Redis']['Status']['Keys']))
                    $Output[] = ['<l>IO.Storage.Redis.Status:'.strtr($Key, '_', '.').'</l>', $Value];
        }

        $Call['Output']['Content'][] =
            [
                'Type' => 'Table',
                'Value' => $Output
            ];

        return $Call;
    });