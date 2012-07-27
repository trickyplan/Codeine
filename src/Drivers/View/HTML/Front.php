<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Layouts', function ($Call)
    {
        if (!isset($Call['Layouts']))
            $Call['Layouts'] = array();

        if (F::isCall($Call))
        {
            $Slices = explode('.', $Call['Service']);

            $sz = sizeof($Slices);

            $Asset = $Slices[0];


            for ($ic = 1; $ic < $sz; $ic++)
                $IDs[$ic] = implode('/', array_slice($Slices, 1, $ic));

            $IDs[] = 'Main';

            foreach ($IDs as $ID)
                array_unshift($Call['Layouts'], array ('Scope' => $Asset, 'ID'    => $ID, 'Context' => $Call['Context']));
        }

        if (isset($Call['Zone']))
        {
            $Call['Zone'] = (array) $Call['Zone'];

            foreach ($Call['Zone'] as $Zone)
                array_unshift($Call['Layouts'], array('Scope' => $Zone, 'ID' => 'Zone'));
        }

        return $Call;
     });