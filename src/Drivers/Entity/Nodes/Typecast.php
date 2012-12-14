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
                if (isset($Call['Nodes'][$Key]['Type']))
                {
                    if (is_array($Value))
                        foreach ($Value as $Relation => &$cValue)
                            if (!is_array($cValue))
                                $Where[$Key][$Relation] =
                                    F::Run('Data.Type.'.$Call['Nodes'][$Key]['Type'], 'Read',
                                        ['Value' => $cValue, 'Purpose' => 'Where']);
                            else
                                $Where[$Key][$Relation] =$cValue;
                    else
                        $Where[$Key] = F::Run('Data.Type.'.$Call['Nodes'][$Key]['Type'], 'Read',
                            ['Value' => $Value, 'Purpose' => 'Where']);
                }
                    $Where[$Key] = $Value;
            }
            $Call['Where'] = $Where;
        }

        return $Call;
    });