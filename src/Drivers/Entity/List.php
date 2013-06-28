<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        if (!isset($Call['Context'])) $Call['Context'] = '';

        $Call = F::Merge($Call, F::loadOptions($Call['Entity'].'.Entity')); // FIXME

        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where']); // FIXME

        $Call = F::Hook('beforeList', $Call);

        $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope']: $Call['Scope'] = $Call['Entity'];
        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']);
        $Call['Layouts'][] = array('Scope' => $Call['Scope'],'ID' => isset($Call['Custom Templates']['List'])? $Call['Custom Templates']['List'] :'List','Context' => $Call['Context']);

        $Call['Locales'][] = $Call['Entity'];

        if (!isset($Call['Elements']))
            $Call['Elements'] = F::Run('Entity', 'Read', $Call);

        if (!isset($Call['Selected']))
            $Call['Selected'] = null;

        if ((sizeof($Call['Elements']) == 0 or (null === $Call['Elements'])) and !isset($Call['NoEmpty']))
        {
            $Empty = isset($Call['Custom Templates']['Empty'])? $Call['Custom Templates']['Empty']: 'Empty';

            $Call['Output']['Content'][] = ['Type' => 'Template', 'Scope' => 'Entity', 'Entity' => $Call['Entity'],  'ID' => $Empty];
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
                $Call['Elements'] = array_reverse($Call['Elements'], true);

            if (is_array($Call['Elements']))
                foreach ($Call['Elements'] as $IX => $Element)
                {
                    if (!isset($Element['ID']))
                        $Element['ID'] = $IX;

                    if (isset($Call['Page']) && isset($Call['EPP']))
                        $Element['IX'] = $Call['EPP']*($Call['Page']-1)+$IX+1;
                    else
                        $Element['IX'] = $IX+1;

                    if (isset($Call['Show Redirects']) or !isset($Element['Redirect']) or empty($Element['Redirect']))
                    {
                        if ($Call['Selected'] == $Element['ID'] or $Call['Selected'] == '*')
                            $Selected = '.Selected';
                        else
                            $Selected = '';

                        $Call['Output']['Content'][] =
                            array(
                                'Type'  => 'Template',
                                'Scope' => $Call['Scope'],
                                'ID' => 'Show/'
                                    .(isset($Call['Template'])? $Call['Template']: 'Short')
                                    .$Selected,
                                // FIXME Strategy of selecting templates
                                'Data'  => $Element
                            );
                    }
                }
        }

        $Call = F::Hook('afterList', $Call);

        unset($Call['Elements']);

        return $Call;
    });

    setFn('RAW', function ($Call)
    {
        $Output = [];

        $Call = F::Merge($Call, F::loadOptions($Call['Entity'].'.Entity')); // FIXME

        $Call = F::Hook('beforeRAWList', $Call);

        $Elements = F::Run('Entity', 'Read', $Call);

        if ($Elements !== null)
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