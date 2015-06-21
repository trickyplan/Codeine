<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Do', function ($Call)
     {
         F::Log('Search Queries: '.F::Run('Entity', 'Count', $Call,
            [
                'Entity' => 'Search.Query'
            ]), LOG_NOTICE);

        $Queries = F::Run('Entity', 'Read', $Call,
            [
                'Entity' => 'Search.Query'
            ]);

        foreach ($Queries as $Query)
        {
            if (empty($Query['Query']))
                ;
            else
                F::Run('Search', 'Query', [
                    'Query' => $Query['Query']
                ]);
        }
     });