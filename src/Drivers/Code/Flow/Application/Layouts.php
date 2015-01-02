<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (!isset($Call['Layouts']))
            $Call['Layouts'] = [];

        if (F::isCall($Call['Run']))
        {
            $Slices = explode('.', $Call['Run']['Service']);

            $sz = sizeof($Slices);

            $Asset = $Slices[0];

            for ($ic = 1; $ic < $sz; $ic++)
                $IDs[$ic] = implode('/', array_slice($Slices, 1, $ic));

            $IDs[] = 'Main';

            foreach ($IDs as $ID)
                array_unshift($Call['Layouts'], ['Scope' => $Asset, 'ID' => $ID, 'Context' => 'app']);
        }

        return $Call;
     });