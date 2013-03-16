<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (!isset($Call['Layouts']))
            $Call['Layouts'] = [];

        if (!isset($Call['Run']['Context']))
            $Call['Run']['Context'] = '';

        if (F::isCall($Call['Run']))
        {
            $Slices = explode('.', $Call['Run']['Service']);

            $sz = sizeof($Slices);

            $Asset = $Slices[0];

            for ($ic = 1; $ic < $sz; $ic++)
                $IDs[$ic] = implode('/', array_slice($Slices, 1, $ic));

            $IDs[] = 'Main';

            foreach ($IDs as $ID)
                array_unshift($Call['Layouts'], array ('Scope' => $Asset, 'ID'    => $ID, 'Context' => $Call['Run']['Context']));
        }

        if (!isset($Call['Run']['Zone']))
            $Call['Run']['Zone'] = ['Project'];
        else
            $Call['Run']['Zone'] = (array) $Call['Run']['Zone'];

            foreach ($Call['Run']['Zone'] as $Zone)
                array_unshift($Call['Layouts'], array('Scope' => $Zone, 'ID' => 'Zone', 'Context' => $Call['Run']['Context']));

        array_unshift($Call['Layouts'], array(
            'Scope' => 'Default',
            'ID' => 'Main',
            'Context' => $Call['Run']['Context']
        ));

        return $Call;
     });