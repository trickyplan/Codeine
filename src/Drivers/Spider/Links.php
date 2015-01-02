<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        phpQuery::newDocumentHTML($Call['Body']);

        $Call['URLs'] = [];

        phpQuery::each(pq('a'),function($Index, $Element) use (&$Call)
        {
            $URL = parse_url($Element->getAttribute('href'));

            if (!isset($URL['scheme']) || $URL['scheme'] == 'http')
            {
                if (isset($URL['host']) && 'http://'.$URL['host'] != $Call['Host'])
                    $Decision = false;
                else
                    $Decision = true;

                $URL = (isset($URL['path'])? $URL['path']: '');

                if (in_array($URL, $Call['Processed']))
                    $Decision = false;

                if (isset($Call['White']) && !preg_match($Call['White'], $URL))
                    $Decision = false;

                if (isset($Call['Black']) && preg_match($Call['Black'], $URL))
                    $Decision = false;

                if (substr($URL, 0, 1) != '/')
                    $URL = '/'.$URL;

                if ($Decision)
                    $Call['URLs'][] = $URL;
            }
        });

        phpQuery::unloadDocuments();

        return $Call;
    });