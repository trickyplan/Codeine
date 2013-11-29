<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('beforeOperation', function ($Call)
    {
        // Если в Where скалярная переменная - это ID.
        if (isset($Call['Where']))
        {
            $Call['Where'] = F::Live($Call['Where'], $Call);

            if (is_scalar($Call['Where']))
            {
                if (strpos($Call['Where'], ',') !== false)
                    $Call['Where'] = ['ID' => explode(',', $Call['Where'])];
                else
                    $Call['Where'] = ['ID' => $Call['Where']];
            }

            $Where = [];

            if (isset($Call['Nodes']))
                foreach ($Call['Nodes'] as $Name => $Node)
                    if (($Value = F::Dot($Call['Where'], $Name)) !== null)
                    {
                        if (isset($Node['Type']))
                        {
                            if (is_array($Value))
                            {
                                foreach ($Value as $Relation => $cValue)
                                    if (!is_array($cValue))
                                        $Value[$Relation]
                                            = F::Run('Data.Type.'.$Node['Type'], 'Where',
                                               [
                                                   'Name' => $Name,
                                                   'Node' => $Node,
                                                   'Value' => $cValue
                                               ]);
                                    else
                                        $Value[$Relation] = $cValue;
                                // FIXME Нативные массивы?
                            }
                            else
                            {
                                $Value = F::Run('Data.Type.'.$Node['Type'], 'Where',
                                    [
                                        'Name' => $Name,
                                        'Node' => $Node,
                                        'Value' => $Value
                                    ]);
                            }
                        }

                        $Where[$Name]  = $Value;
                    }

            if (empty($Where))
                $Call['Where'] = null;
            else
                $Call['Where'] = $Where;


        }

        return $Call;
    });
