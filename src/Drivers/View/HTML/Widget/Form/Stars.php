<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Call['View']['HTML'] = '';
        for ($IC = 1; $IC <= $Call['Stars']; $IC++)
        {
            $StarData = ['Num' => $IC];

            if (isset($Call['Value']) && $Call['Value'] == $IC)
                $StarData['Checked'] = 'checked';

            $Call['View']['HTML'].=  F::Run('View', 'Load',
                [
                    'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                    'ID' => 'Form/Star',
                    'Data' => F::Merge($Call, $StarData)
                ]);
        }

        $Call['Value'] = $Call['View']['HTML'];
        return $Call;
     });