<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Where']))
        {
            $Where = [];

            foreach ($Call['Where'] as $Key => &$Value)
            {
                if (!isset($Call['Nodes'][$Key]['Type']))
                    $Call['Nodes'][$Key]['Type'] = 'Dummy';

                if (is_array($Value))
                    foreach ($Value as $Relation => &$cValue)
                        $Where[$Key][$Relation] =
                            F::Run('Data.Type.'.$Call['Nodes'][$Key]['Type'], 'Read', array('Value' => $cValue, 'Purpose' => 'Where'));
                else
                    $Where[$Key] = F::Run('Data.Type.'.$Call['Nodes'][$Key]['Type'], 'Read', array('Value' => $Value, 'Purpose' => 'Where'));
            }
            $Call['Where'] = $Where;
        }

        return $Call;
    });