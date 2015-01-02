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

        if (is_array($Call['Sitemap']['URL Field']))
            $Call['Fields'] = array_merge($Call['Sitemap']['URL Field'], ['ID']);
        else
            $Call['Fields'] = [$Call['Sitemap']['URL Field'], 'ID'];

        $Elements = F::Run('Entity', 'Read', $Call, ['Partial' => true]);

        if (count($Elements) > 0)
            foreach ($Elements as $Element)
            {
                if (is_array($Call['Sitemap']['URL Field']))
                {
                    $Slug = [];
                    foreach ($Call['Sitemap']['URL Field'] as $Field)
                        if (isset($Element[$Field]))
                            $Slug[] = urlencode($Element[$Field]);

                    $Slug = implode('/', $Slug);
                }
                else
                {
                    if (isset($Element[$Call['Sitemap']['URL Field']]))
                        $Slug = urlencode($Element[$Call['Sitemap']['URL Field']]);
                    else
                        $Slug = $Element['ID'];
                }

                $Call['Output']['Content'][] =
                [
                    'url' =>
                    [
                        'loc' => $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/'.$Call['Scope'].'/'.$Slug,
                        'lastmod' => date(DATE_W3C),
                        'changefreq' => $Call['Frequency'],
                        'priority'   => $Call['Priority']
                    ]
                ]; // FIXME!
            }

        return $Call;
    });