<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        $Call['Data'] = F::Run('Entity', 'Read', $Call)[0];

        $Call['Locales'][] = $Call['Entity'];

        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']);
        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Show','Context' => $Call['Context']);

        if (empty($Call['Data']))
            $Call = F::Run('Error/404', 'Page', $Call);
        else
        {
            $Call['Output']['Content'][] = array (
                'Type'  => 'Template',
                'Scope' => isset($Call['Scope'])? $Call['Scope'] :$Call['Entity'],
                'ID' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Full'),
                'Data' => $Call['Data']
            );
        }

        return $Call;
    });
