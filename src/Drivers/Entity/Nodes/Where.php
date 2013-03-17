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

            foreach ($Call['Nodes'] as $Name => $Node)
                if (($Value = F::Dot($Call['Where'], $Name)) !== null)
                {
                    if (isset($Node['Type']))
                    {
                        if (is_array($Value))
                        {
                            foreach ($Value as $Relation => $cValue)
                                if (!is_array($cValue))
                                    $Value[$Relation] = F::Run('Data.Type.'.$Node['Type'], 'Read',
                                                               [
                                                               'Node' => $Node,
                                                               'Value' => $cValue,
                                                               'Purpose' => 'Where'
                                                               ]);
                                else
                                    $Value[$Relation] = $cValue;
                            // FIXME Нативные массивы?
                        }
                        else
                            $Value = F::Run('Data.Type.'.$Node['Type'], 'Read', ['Node' => $Node, 'Value' => $Value, 'Purpose' => 'Where']);
                    }

                    $Where = F::Dot($Where, $Name, $Value);
                }

            $Call['Where'] = $Where;
        }

        return $Call;
    });