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

        if (F::isCall($Call['Run']))
        {
            $Slices = explode('.', $Call['Run']['Service']);

            $sz = sizeof($Slices);

            $Asset = $Slices[0];

            for ($ic = 1; $ic < $sz; $ic++)
                $IDs[$ic] = implode('/', array_slice($Slices, 1, $ic));

            if (isset($Call['Run']['Call']['Entity']))
                array_unshift($Call['Layouts'],
                [
                    'Scope' => $Call['Run']['Call']['Entity'],
                    'ID'    => 'Main',
                    'Context' => $Call['Context']
                ]);

            $IDs[] = 'Main';

            foreach ($IDs as $ID)
                array_unshift($Call['Layouts'],
                    [
                        'Scope' => $Asset,
                        'ID'    => $ID,
                        'Context' => $Call['Context']
                    ]);

            if (!isset($Call['Run']['Zone']))
                $Call['Run']['Zone'] = ['Project'];
            else
                $Call['Run']['Zone'] = (array) $Call['Run']['Zone'];

                foreach ($Call['Run']['Zone'] as $Zone)
                    array_unshift($Call['Layouts'],
                        [
                            'Scope' => $Zone,
                            'ID' => 'Zone',
                            'Context' => $Call['Context']
                        ]);
        }
        else
        {
            array_unshift($Call['Layouts'],
                        [
                            'Scope' => 'Project',
                            'ID' => 'Zone',
                            'Context' => $Call['Context']
                        ]);
        }


        return $Call;
     });