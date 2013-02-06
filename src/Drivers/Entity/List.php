<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Merge($Call, F::loadOptions($Call['Entity'].'.Entity')); // FIXME

        $Call = F::Hook('beforeList', $Call);

        if (!isset($Call['Elements']))
        {
            if (isset($Call['Where']))
                $Call['Where'] = F::Live($Call['Where']); // FIXME

            $Call['Elements'] = F::Run('Entity', 'Read', $Call);
        }

        $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope']: $Call['Scope'] = $Call['Entity'];

        $Call['Layouts'][] = array('Scope' => $Call['Scope'],'ID' => 'Main','Context' => $Call['Context']);
        $Call['Layouts'][] = array('Scope' => $Call['Scope'],'ID' => isset($Call['Custom Templates']['List'])? $Call['Custom Templates']['List'] :'List','Context' => $Call['Context']);

        $Call['Locales'][] = $Call['Entity'];

        if (!isset($Call['Selected']))
            $Call['Selected'] = null;

        if ((sizeof($Call['Elements']) == 0 or (null === $Call['Elements'])) and !isset($Call['NoEmpty']))
        {
            $Call['Layouts'][] = ['Scope' => $Call['Scope'], 'ID' => 'Empty'];
            $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Empty'];
        }
        else
        {
            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Scope'],
                    'ID' => (isset($Call['Table'])? $Call['Table']: 'Table'),
                    'Context' => $Call['Context']
                ];

            if (isset($Call['Reverse']))
                $Elements = array_reverse($Call['Elements'], true);

            if (is_array($Call['Elements']))
                foreach ($Call['Elements'] as $IX => $Element)
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

    setFn('RAW', function ($Call)
    {
        $Call = F::Merge($Call, F::loadOptions($Call['Entity'].'.Entity')); // FIXME

        $Call = F::Hook('beforeRAWList', $Call);

        $Elements = F::Run('Entity', 'Read', $Call);

        foreach ($Elements as $Element)
            $Output[] = [$Element[$Call['Primary']], $Element[$Call['Key']]];

        return $Output;
    });

    setFn('RAW2', function ($Call) // FIXME
    {
        $Call = F::Merge($Call, F::loadOptions($Call['Entity'].'.Entity')); // FIXME

        $Call = F::Hook('beforeRAWList', $Call);

        $Elements = F::Run('Entity', 'Read', $Call);

        foreach ($Elements as $Element)
            $Call['Output'][] = [$Element['ID'], $Element[$Call['Key']]];

        return $Call;
    });