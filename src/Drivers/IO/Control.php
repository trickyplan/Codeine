<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'IO',
            'ID' => 'Overview'
        ];

        return $Call;
    });

    setFn('Storages', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'IO',
            'ID' => 'Storages'
        ];

        $IO = F::loadOptions('IO');

        foreach ($IO['Storages'] as $Name => $Storage)
        {
            $Storage['Status'] = (F::Run('IO', 'Open', $Call, ['Storage' => $Name]) !== null);
            $Storage['Size'] = F::Run('IO', 'Execute', $Call, ['Execute' => 'Size', 'Storage' => $Name]);

            $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'IO',
                    'ID' => 'Control/Show/Short',
                    'Data' => F::Merge(['Name' => $Name], $Storage)
                ];
        }

        return $Call;
    });