<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        list($Call[$Call['Entity']]) = F::Run('Entity', 'Read', $Call);

        $Call['Locales'][] = $Call['Entity'];

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Main'
                );

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Show'
                );

        if (empty($Call[$Call['Entity']]))
            $Call = F::Run('Error/404', 'Page', $Call);
        else
        {
            $Call['Output']['Content'][] = array (
                'Type'  => 'Template',
                'Scope' => $Call['Entity'],
                'ID' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Full'),
                'Data' => $Call[$Call['Entity']]
            );
        }

        return $Call;
    });
