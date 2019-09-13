<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Add Entity to Index', function ($Call)
    {
        $Call = F::loadOptions('Search', null, $Call);
        
        if (isset($Call['Search']['Provider']['Available'][$Call['Entity']]))
        {
            // Remove $Call['Data']['Indexable']
            
            $Data = ['ID' => $Call['Data']['ID']];
            
            foreach ($Call['Nodes'] as $Name => $Node)
                if (F::Dot($Node, 'Search.Allowed'))
                {
                    $Value = F::Dot($Call['Data'], $Name);
                    $Name = strtr($Name, '.', '_');
                    if (empty($Value))
                        ;
                    else
                    {
                        if (is_array($Value))
                            $Data[$Name] = j($Value);
                        else
                            $Data[$Name] = $Value;
                    }
                }

             $Result = F::Run('Search', 'Document.Create', $Call,
                 [
                    'Provider'  => $Call['Entity'],
                    'Search'    =>
                    [
                        'Index'    => $Call['Entity']
                    ],
                    'Data!'      => $Data
                ]);

            if ($Result)
                F::Log(function () use ($Result) {return 'Indexed: '.j($Result);} , LOG_DEBUG, ['Developer', 'Search']);
            else
                F::Log('Not indexed: '.j($Result), LOG_WARNING, ['Developer', 'Search']);
        }
        return $Call;
    });

    setFn('Remove', function ($Call)
    {
        if (isset($Call['Data']))
            ;
        else
            $Call['Data'] = F::Run('Entity', 'Read', $Call);

        if (F::Run('Search', 'Remove', $Call,
        [
            'Provider' => $Call['Entity'],
            'Data!'    => ['ID' => $Call['Data']['ID']],
            'Search'    =>
            [
                'Index'    => $Call['Entity']
            ]
        ]))
        {
            F::Log($Call['Entity'].' '.$Call['Data']['ID'].' removed', LOG_INFO);
            F::Log(function () use ($Call) {return $Call['Data'];} , LOG_DEBUG);
        }

        return $Call;
    });

