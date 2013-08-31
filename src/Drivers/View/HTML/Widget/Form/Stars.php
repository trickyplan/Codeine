<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Call['Value'] = '';

        for ($IC = 1; $IC <= $Call['Stars']; $IC++)
        {
            $StarData = ['Num' => $IC];

            if (isset($Call['Value']) && $Call['Value'] == $IC)
                $StarData['Checked'] = true;

            $Call['Value'].=  F::Run('View', 'Load',
                [
                    'Scope' => $Call['Widget Set'].'/Widgets',
                    'ID' => 'Form/Star',
                    'Data' => F::Merge($Call, $StarData)
                ]);
        }

        return $Call;
     });