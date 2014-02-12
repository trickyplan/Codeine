<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Query', function ($Call)
    {
/*        $Call['Query'] = F::Run('Text.Index.Metaphone.Russian', 'Get',['Value' => mb_strtolower($Call['Query'])]);*/
        $Call['Query'] = preg_split('/\s/', $Call['Query']);
        $Results = [];

        foreach($Call['Query'] as $Keyword)
        {
            $KeywordResults = F::Run('Entity', 'Read',
                [
                    'Entity' => $Call['Entity'],
                    'Fields' => ['ID', 'Title'],
                    'Where' =>
                    [
                        'Keywords' => $Keyword // FIXME SOON
                    ]
                ]);

            if (is_array($KeywordResults))
                $Results = array_merge($Results, $KeywordResults);
        }

        foreach ($Results as &$Result)
        {
            $Result['From'] = $Call['HTTP']['Host'];
            $Result['URL']  = $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/'.strtolower($Call['Entity']).'/'.$Result['ID'];
        }

        return $Results;
    });