<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Merge(F::Run('Entity', 'Load', $Call), $Call);

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Main'
                );

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'List'
                );

        $Call['Locales'][] = $Call['Entity'];

        $Call['Front']['Count'] = F::Run('Entity', 'Count', $Call);

        $Call = F::Hook('beforeList', $Call);

        if (!isset($Call['Where']) || $Call['Where'] !== false)
            $Elements = F::Run('Entity', 'Read', $Call);
        else
            $Elements = array();

        if (sizeof($Elements) == 0)
            $Call['Output']['Content'][] = array(
                'Type'  => 'Template',
                'Context' => $Call['Context'],
                'Scope' => $Call['Entity'],
                'Value' => 'Empty'
            );
        else
        {
            if (!isset($Call['Selected']))
                $Call['Selected'] = null;

            foreach ($Elements as $ID => $Element)
            {
                if (!isset($Element['ID']))
                    $Element['ID'] = $ID;

                $Call['Output']['Content'][] = array(
                    'Type'  => 'Template',
                    'Scope' => $Call['Entity'],
                    'Value' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Short').($Call['Selected'] == $Element['ID'] ? '.Selected': ''),
                    'Data' => $Element
                );
            }
        }


        $Call = F::Hook('afterList', $Call);

        return $Call;
    });