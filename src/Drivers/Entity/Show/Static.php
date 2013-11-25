<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    setFn('Before', function ($Call)
    {
        $Call['Where'] = F::Live($Call['Where']);

        $Call['Data'] = F::Run('Entity', 'Read', $Call, ['One' => true]);

        return $Call;
    });

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeShow', $Call);

        $Call = F::Hook('beforeShowDo', $Call);

        if (isset($Call['Data']['Redirect']) && !empty($Call['Data']['Redirect']))
            $Call = F::Apply('System.Interface.Web','Redirect', $Call, ['Location' => $Call['Data']['Redirect']]);
        else
        {
            $Call['Layouts'][] = ['Scope' => $Call['Entity'],'ID' => 'Show','Context' => $Call['Context']];

            $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];

            if (empty($Call['Data']))
                $Call = F::Hook('NotFound', $Call);
            else
            {
                $Call['Output']['Content'][] = array (
                    'Type'  => 'Template',
                    'Scope' => $Call['Scope'],
                    'ID' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Full'),
                    'Data' => $Call['Data']
                );

                $Call = F::Hook('afterShow', $Call);
            }
        }

        F::Log($Call['Data'], LOG_DEBUG);

        return $Call;
    });
