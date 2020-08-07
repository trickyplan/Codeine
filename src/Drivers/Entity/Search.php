<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call['Provider'] = $Call['Entity'];
        $Call = F::Dot($Call,'Search.Index', $Call['Entity']);

        $Call = F::loadOptions($Call['Entity'].'.Entity', null, $Call);
        $Call = F::Hook('beforeEntitySearch', $Call);

        $Call = F::Apply( 'Search', 'Query', $Call);
        
/*        if (isset($Call['Search']['Provider']['Available'][$Call['Entity']]['Nodes']))
            $Fields = $Call['Search']['Provider']['Available'][$Call['Entity']]['Nodes'];
        else
            $Fields = ['ID', 'Title'];*/

        $Call['Entities'] = F::Run('Entity', 'Read', $Call,
            [
                'Limit!'  => null,
                'Where!'  =>
                [
                    'ID' =>
                    [
                        '$in' => array_keys($Call['Elements'])
                    ]
                ]
            ]
        );

        $Call['Scope'] = isset($Call['Scope'])? strtr($Call['Entity'], '.', '/').'/'.$Call['Scope'] : strtr($Call['Entity'], '.', '/');
        
        $Call['Layouts'][] =
            [
                'Scope' => $Call['Scope'],
                'ID' => isset($Call['Custom Templates']['List'])? $Call['Custom Templates']['List'] :'List',
                'Context' => $Call['Context']
            ];

        $Empty = false;

        $Call['Template'] = (isset($Call['Template'])? $Call['Template']: 'Short');
        
        F::Log('Search template is *'.$Call['Template'].'*', LOG_INFO);

        if (sizeof($Call['Entities']) == 0)
            $Empty = true;

        if (isset($Call['Where']) && $Call['Where'] === [])
            $Empty = true;

        if (null === $Call['Entities'])
            $Empty = true;

        if (isset($Call['NoEmpty']))
            $Empty = false;

        if ($Empty)
        {
            if (F::Dot($Call, 'Search.EmptyAsEmptyArray'))
                $Call['Output']['Content'] = [];
            else
            {
                $Empty = isset($Call['Custom Templates']['Empty'])? $Call['Custom Templates']['Empty']: 'Empty';
                $Call['Output']['Content'][]
                    = ['Type' => 'Template', 'Scope' => $Call['Scope'], 'Entity' => $Call['Entity'],  'ID' => $Empty];
            }

            $Call = F::Hook('Empty', $Call);
        }
        else
        {
            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Scope'],
                    'ID' => (isset($Call['Custom Templates']['Table'])? $Call['Custom Templates']['Table']: 'Table'),
                    'Context' => $Call['Context']
                ];

            if (isset($Call['Reverse']))
                $Call['Entities'] = array_reverse($Call['Entities'], true);

            foreach ($Call['Entities'] as $IX => &$Entity)
                if (isset($Call['Elements'][$Entity['ID']]['_score']))
                    $Entity['Search']['Score'] = (float) $Call['Elements'][$Entity['ID']]['_score'];
                else
                    $Entity['Search']['Score'] = $IX;

            $Call['Entities'] = F::Sort($Call['Entities'], 'Search.Score', SORT_DESC);

            if (is_array($Call['Entities']))
                foreach ($Call['Entities'] as $IX => $Entity)
                {
                    if (!isset($Entity['ID']))
                        $Entity['ID'] = $IX;

                    if (isset($Call['Page']) && isset($Call['EPP']))
                        $Entity['IX'] = $Call['EPP']*($Call['Page']-1)+$IX+1;
                    else
                        $Entity['IX'] = $IX+1;

                    if (isset($Call['Show Redirects']) or !isset($Entity['Redirect']) or empty($Entity['Redirect']))
                    {
                        $Call['Output']['Content'][] =
                            [
                                'Type'  => 'Template',
                                'Scope' => $Call['Scope'],
                                'ID' => 'Show/'
                                    .$Call['Template'],
                                // FIXME Strategy of selecting templates
                                'Data'  => $Entity
                            ];
                    }
                }
        }
        $Call = F::Hook('afterEntitySearch', $Call);

        return $Call;
    });