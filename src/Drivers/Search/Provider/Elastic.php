<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        $Call['Link'] = Elasticsearch\ClientBuilder::create()->build();
        return $Call;
    });

    setFn('Document.Create', function ($Call)
    {
        $Call = F::Run(null, 'Open', $Call);

        try
        {
            $Result = $Call['Link']->index(
                 [
                     'index' => $Call['Elastic Search']['Index'],
                     'type'  => $Call['Elastic Search']['Type'],
                     'id'    => $Call['Data']['ID'],
                     'body'  => $Call['Index']
                 ]);
            F::Log(j($Result), LOG_INFO);
        }
        catch (Exception $e)
        {
            F::Log($e->getMessage(), LOG_ERR);
        }

        return $Result;
    });

    setFn('Count', function ($Call)
    {
        $IDs = [];
        try
        {
            $Call = F::Run(null, 'Open', $Call);
            $Call['Query'] = mb_substr($Call['Query'], 0, 32);

            F::Log('Start Count on query: '.$Call['Query'], LOG_NOTICE);

            $Query = [
                     'index' => $Call['Elastic Search']['Index'],
                     'type'  => $Call['Elastic Search']['Type'],
                     'body'  =>
                     [
                          'query' => F::Live($Call['Search']['Query']['Default'], $Call)
                      ]
                 ];
            
            F::Log($Query, LOG_INFO, 'Administrator');
            $Results = $Call['Link']->count($Query);
            
            $Total = $Results['count'];
        }
        catch (Exception $e)
        {
            F::Log($e->getMessage(), LOG_CRIT, 'Developer');
            $Total = 0;
        }
        
        return $Total;
    });
    
    setFn('Query', function ($Call)
    {
        $IDs = [];
        try
        {
            $Call = F::Run(null, 'Open', $Call);
            $Call['Query'] = mb_substr($Call['Query'], 0, 32);

            F::Log('Start search on query: '.$Call['Query'], LOG_NOTICE);

            $Query = [
                     'index' => $Call['Elastic Search']['Index'],
                     'type'  => $Call['Elastic Search']['Type'],
                     'from'  => $Call['Limit']['From'],
                     'size'  => $Call['Limit']['To'],
                     'body'  =>
                     [
                          'query' => F::Live($Call['Search']['Query']['Default'], $Call),
                          'highlight' =>
                          [
                              'fields' =>
                              [
                                  $Call['Highlight'] =>
                                  [
                                      'pre_tags'  => ['<em class="highlight">'],
                                      'post_tags' => ['</em>'],
                                      'fragment_size' => 240, // FIXME
                                      'number_of_fragments' => 1 // FIXME
                                  ]
                              ]
                          ]
                      ]
                 ];
            F::Log($Query, LOG_INFO, 'Administrator');
            $Results = $Call['Link']->search($Query);
    
            foreach ($Results['hits']['hits'] as $Hit)
                $IDs[$Hit['_id']] = $Hit;
        }
        catch (Exception $e)
        {
            F::Log($e->getMessage(), LOG_CRIT, 'Developer');
            $Results = ['hits' => ['total' => 0]];
        }

        F::Log('Total hits: '.$Results['hits']['total'], LOG_INFO);
        $Meta = ['Hits' => [$Call['Scope'] => $Results['hits']['total']]];
        
        return ['Meta' => $Meta, 'IDs' => $IDs];
    });

    setFn('Document.Delete', function ($Call)
    {
        try
        {
            $Call = F::Run(null, 'Open', $Call);
            F::Log($Call['Link']->delete(
                 [
                     'index' => $Call['Index'],
                     'id'    => $Call['Data']['ID'],
                     'type'  => $Call['Type']
                 ]
            ), LOG_INFO);
        }
        catch (Exception $e)
        {
            F::Log('Exception: '.$e->getMessage().'. Remove data from index', LOG_ERR);
        }
        return $Call;
    });

