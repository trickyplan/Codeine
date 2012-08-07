<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Merge(F::loadOptions($Call['Entity'].'.Entity'), $Call); // FIXME
        $Call = F::Hook('beforeList', $Call);

        $Call['Where'] = F::Live($Call['Where']); // FIXME

        $Elements = F::Run('Entity', 'Read', $Call);

        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']);
        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'List','Context' => $Call['Context']);

        $Call['Locales'][] = $Call['Entity'];

        if (!isset($Call['Selected']))
            $Call['Selected'] = null;

        if (sizeof($Elements) == 0)
            $Call['Output']['Content'][] = array(
                'Type'  => 'Template',
                'Context' => $Call['Context'],
                'Scope' => $Call['Entity'],
                'ID' => 'Empty'
            );
        else
        {
            if (isset($Call['Reverse']))
                $Elements = array_reverse($Elements, true);

            foreach ($Elements as $IX => $Element)
            {
                if (!isset($Element['ID']))
                    $Element['ID'] = $IX;

                $Call['Output']['Content'][] =
                    array(
                        'Type'  => 'Template',
                        'Scope' => $Call['Entity'],
                        'ID' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Short').($Call['Selected'] === $Element['ID'] ? '.Selected': ''),
                        // FIXME Strategy of selecting templates
                        'Data'  => $Element
                    );
            }
        }

        $Call = F::Hook('afterList', $Call);

        return $Call;
    });

