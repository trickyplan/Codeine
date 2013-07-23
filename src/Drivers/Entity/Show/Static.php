<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Merge($Call, F::loadOptions($Call['Entity'].'.Entity')); // FIXME

        $Call = F::Hook('beforeShow', $Call);

        $Call['Where'] = F::Live($Call['Where']);
        $Call['Limit'] = ['From' => 0, 'To' => 1];

        if (!isset($Call['Data']))
            $Call['Data'] = F::Run('Entity', 'Read', $Call)[0];

        if (isset($Call['Data']['Redirect']) && !empty($Call['Data']['Redirect']))
            $Call = F::Run('System.Interface.Web','Redirect', $Call, ['Location' => $Call['Data']['Redirect']]);
        else
        {
            $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']);
            $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Show','Context' => $Call['Context']);

            $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope']: $Call['Scope'] = $Call['Entity'];

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

        return $Call;
    });
