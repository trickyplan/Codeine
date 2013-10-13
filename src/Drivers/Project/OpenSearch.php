<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Root'] = 'OpenSearchDescription';
        $Call['Namespace'] = 'http://a9.com/-/spec/opensearch/1.1/';

        $Call['Output']['Content']['ShortName'] = 'Поиск по '.$Call['Project']['Title'];
        $Call['Output']['Content']['Description'] = $Call['Project']['Description']['Short'];

        $Call['Output']['Content']['Contact'] = $Call['Project']['Contacts']['Search']['EMail'];

        $Call['Output']['Content']['URL'] =
            [
                'type' => 'text/html',
                'template' => $Call['Host'].'/search?Query={searchTerms}'
            ];

        return $Call;
    });

/*
 *  <OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
   <ShortName>Web Search</ShortName>
   <Description>Use Example.com to search the Web.</Description>
   <Tags>example web</Tags>
   <Contact>admin@example.com</Contact>
   <Url type="application/rss+xml"
        template="http://example.com/?q={searchTerms}&amp;pw={startPage?}&amp;format=rss"/>
 </OpenSearchDescription>
 */