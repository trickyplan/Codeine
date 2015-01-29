<?php

    /* Sphinx
     * @author bergstein@trickyplan.com
     * @description Sitemap generator 
     * @version 7.0
     */

    setFn('Generate', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', ['Entity' => 'Search.Query', 'Fields' => ['Query']]);
        $Data = [];

        if ($Elements !== null)
        foreach ($Elements as $Element)
            $Data[] = $Call['HTTP']['Proto'].$Call['HTTP']['Host'].'/'.$Call['Scope'].'/'.$Element['Query']; // FIXME!

        return $Data;
     });