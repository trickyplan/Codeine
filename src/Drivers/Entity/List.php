<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Merge($Call, F::loadOptions('Entity.'.$Call['Entity']));

        $Call['Output']['Content'][] = array(
                    'Type'  => 'Template',
                    'Scope' => $Call['Entity'],
                    'Value' => 'Main'
                );

        $Call['Output']['Content'][] = array(
                    'Type'  => 'Template',
                    'Scope' => $Call['Entity'],
                    'Value' => 'List'
                );

        $Call = F::Hook('beforeList', $Call);

        $Elements = F::Run('Entity', 'Read', $Call);

        if (sizeof($Elements) == 0)
            $Call['Output']['Content'][] = array(
                'Type'  => 'Template',
                'Scope' => $Call['Entity'],
                'Value' => 'Empty'
            );
        else
        {
            foreach ($Elements as $Element)
                $Call['Output']['Content'][] = array(
                    'Type'  => 'Template',
                    'Scope' => $Call['Entity'],
                    'Value' => 'Show.'.(isset($Call['Template'])? $Call['Template']: 'Short'),
                    'Data' => $Element
                );
        }

        $Call = F::Hook('afterList', $Call);

        return $Call;
    });