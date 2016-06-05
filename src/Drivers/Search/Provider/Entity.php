<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Query', function ($Call)
    {
        if (isset($Call['Scope']))
            $Call['Scope'] = $Call['Entity'].'/'.$Call['Scope'];
        else
            $Call['Scope'] = $Call['Entity'];

        $Call['Query'] = preg_replace('/[^\w \d]+/SsUu', ' ', $Call['Query']);

        $Call['Query'] = preg_split('/\s/', mb_strtolower($Call['Query']));
        $Results = [];

        $Call = F::Apply('Entity', 'Load', $Call, ['Entity' => $Call['Entity']]);

        $Relevance = [];

        foreach($Call['Query'] as $Keyword)
        {
            $KeywordResults = F::Run('Entity', 'Read',
                [
                    'Entity' => $Call['Entity'],
                    'Fields' => $Call['Show fields'],
                    'Where'  =>
                    [
                        'Keywords' => $Keyword // FIXME SOON
                    ]
                ]);

            if (is_array($KeywordResults))
                foreach ($KeywordResults as $KeywordResult)
                    $Results[$KeywordResult['ID']] = $KeywordResult;

            $IDs = F::Extract($KeywordResults, 'ID');
            sort($IDs);
            $Relevance = array_merge($Relevance, $IDs);
        }
        $Relevance = array_count_values($Relevance);
        arsort($Relevance);
        $Relevance = array_keys($Relevance); // FIXMEEEEE

        $SERP = [];

        if (empty($Results))
            $SERP[$Call['Scope']] =
                [
                    'Type'  => 'Template',
                    'Scope' => $Call['Scope'],
                    'ID'    => 'Empty'
                ];
        else
            foreach ($Relevance as $RankedID)
            {
                $Result = $Results[$RankedID];
                $Result['From'] = $Call['HTTP']['Host'];
                $Result['URL']  = $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/'.$Call['Slug']['Entity'].'/'.$Result['ID'];
                $SERP[$Result['URL']] =
                    [
                        'Type'  => 'Template',
                        'Scope' => $Call['Scope'],
                        'ID'    => 'Show/Search',
                        'Data'  => $Result
                    ];

            }

        $Meta = ['Hits' => [$Call['Scope'] => count($Results)]];
        return ['SERP' => $SERP, 'Meta' => $Meta];
    });