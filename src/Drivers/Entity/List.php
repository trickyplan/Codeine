<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Merge(F::loadOptions('Entity.'.$Call['Entity']), $Call); // FIXME
        $Call = F::Hook('beforeList', $Call);

        $Elements = F::Run('Entity', 'Read', $Call);

        if (!isset($Call['NoEntityLayouts']))
        {
            $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main');
            $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'List');
        }

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

