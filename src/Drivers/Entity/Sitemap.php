<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Index', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);
        $ElementsCount = F::Run('Entity', 'Count', $Call);

        $PC = ceil($ElementsCount/$Call['Sitemap']['URLs']);

        if ($PC > 1)
            for($A = 1; $A <= $PC; $A++)
                $Call['Output']['Content'][] =
                [
                    'sitemap' =>
                    [
                        'loc' => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap/'.$Call['Entity'].'/'.$A.'.xml'
                    ]
                ]; // FIXME!
        else
            $Call['Output']['Content'][] =
                [
                    'sitemap' =>
                    [
                        'loc' => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/sitemap/'.$Call['Entity'].'.xml'
                    ]
                ];

        return $Call;
    });

    setFn('One', function ($Call)
    {
        if (isset($Call['Scope']))
            ;
        else
            $Call['Scope'] = strtolower($Call['Entity']);

        if (isset($Call['Page']))
            ;
        else
            $Call['Page'] = 1;

        $Call['Limit'] = ['From' => ($Call['Page']-1)*$Call['Sitemap']['URLs'], 'To' => $Call['Sitemap']['URLs']];

        $Elements = F::Run('Entity', 'Read', $Call, ['Fields' => [$Call['Sitemap']['URL Field'], 'ID'], 'Partial' => true]);

        if (count($Elements) > 0)
            foreach ($Elements as $Element)
            {
                if (isset($Element[$Call['Sitemap']['URL Field']]))
                    ;
                else
                    $Element[$Call['Sitemap']['URL Field']] = $Element['ID'];

                $Call['Output']['Content'][] =
                [
                    'url' =>
                    [
                        'loc' => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/'.$Call['Scope'].'/'.$Element[$Call['Sitemap']['URL Field']],
                        'lastmod' => date(DATE_W3C),
                        'changefreq' => $Call['Frequency'],
                        'priority'   => $Call['Priority']
                    ]
                ]; // FIXME!
            }

        return $Call;
    });