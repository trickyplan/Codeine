<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    self::setFn('Do', function ($Call)
    {
        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where']); // FIXME

        $Call = F::Merge(F::loadOptions($Call['Entity'].'.Entity'), $Call); // FIXME
        $Call = F::Hook('beforeList', $Call);

        $Elements = F::Run('Entity', 'Read', $Call);

        if (!isset($Call['Scope']))
            $Call['Scope'] = $Call['Entity'];
        else
            $Call['Scope'] = $Call['Entity'].'/'.$Call['Scope'];

        $Call['Layouts'][] = array('Scope' => $Call['Scope'],'ID' => 'Main','Context' => $Call['Context']);
        $Call['Layouts'][] = array('Scope' => $Call['Scope'],'ID' => 'List','Context' => $Call['Context']);

        $Call['Locales'][] = $Call['Entity'];

        if (!isset($Call['Selected']))
            $Call['Selected'] = null;

        if (sizeof($Elements) == 0)
            $Call['Output']['Content'][] = array(
                'Type'  => 'Template',
                'Context' => $Call['Context'],
                'Scope' => $Call['Scope'],
                'ID' => 'Empty'
            );
        else
        {
            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Scope'],
                    'ID' => (isset($Call['Table'])? $Call['Table']: 'Table'),
                    'Context' => $Call['Context']
                ];

            if (isset($Call['Reverse']))
                $Elements = array_reverse($Elements, true);

            foreach ($Elements as $IX => $Element)
            {
                if (!isset($Element['ID']))
                    $Element['ID'] = $IX;

                $Element['IX'] = $IX+1;

                $Call['Output']['Content'][] =
                    array(
                        'Type'  => 'Template',
                        'Scope' => $Call['Scope'],
                        'ID' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Short').($Call['Selected'] === $Element['ID'] ? '.Selected': ''),
                        // FIXME Strategy of selecting templates
                        'Data'  => $Element
                    );
            }
        }

        $Call = F::Hook('afterList', $Call);

        return $Call;
    });

    self::setFn('RAW', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', $Call);

        foreach ($Elements as $Element)
            $Output[$Element['ID']] = $Element[$Call['Key']];

        return $Output;
    });