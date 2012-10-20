<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        $Call['Where'] = F::Live($Call['Where']);

        $Call['Data'] = F::Run('Entity', 'Read', $Call)[0];

        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']);
        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Show','Context' => $Call['Context']);

        $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope']: $Call['Scope'] = $Call['Entity'];

        if (empty($Call['Data']))
            $Call = F::Run('Error/404', 'Page', $Call);
        else
        {
            $Call['Output']['Content'][] = array (
                'Type'  => 'Template',
                'Scope' => $Call['Scope'],
                'ID' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Full'),
                'Data' => $Call['Data']
            );
        }

        return $Call;
    });
