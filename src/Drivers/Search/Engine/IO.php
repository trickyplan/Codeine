<?php

    /* Codeine
     * @author BreathLess
     * @description Sphinx Driver 
     * @package Codeine
     * @version 7.x
     */

    setFn('Query', function ($Call)
    {
        $Call['Query'] = F::Run('Text.Index.Metaphone.Russian', 'Get',['Value' => mb_strtolower($Call['Query'])]);
        $Call['Query'] = preg_split('/\s/', $Call['Query']);

        $SERP = [];

        foreach ($Call['Query'] as $Keyword)
        {
            $cSERP[$Keyword] = F::Run('IO', 'Read', $Call['Engines'][$Call['Engine']],
                [
                    'Fields' => ['ID'],
                    'Scope' => $Call['Entity'],
                    'Where' =>
                        [
                            'Keywords' => '~/'.$Keyword.'/' // FIXME SOON
                        ]
                ]);

            if (null === $cSERP[$Keyword])
                ;
            else
            {
                foreach ($cSERP[$Keyword] as $cResult)
                    if (null !== $cResult)
                        $SERP[] = $cResult['ID'];
            }
        }

        $SERP = array_count_values($SERP);
        arsort($SERP);

        if (empty($SERP))
        {
            // F::Log('*No results* for *'.$Call['Query'].'* in *'.$Call['Entity'].'*', LOG_INFO);
            $Result = null;
        }
        else
        {
            $Result = $SERP;
            F::Log('*'.count($Result).' results* ', LOG_INFO);
        }

        return $Result;
    });

    setFn('Add', function ($Call)
    {

        return $Call;
    });