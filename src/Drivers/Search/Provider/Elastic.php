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
        $Call['Search']['Index'] = mb_strtolower($Call['Search']['Index']);
        return $Call;
    });

    setFn('Document.Create', function ($Call)
    {
        $Call = F::Run(null, 'Open', $Call);

        $Result = null;

        try
        {
            $Result = $Call['Link']->index(
                    [
                        'index' => $Call['Search']['Index'],
                        'id'    => $Call['Data']['ID'],
                        'body'  => $Call['Data'],
                        'type'  => '_doc'
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
                     'index' => $Call['Search']['Index'],
                     'body'  =>
                     [
                          'query' =>
                          [
                              'multi_match' =>
                              [
                                  'query'  => $Call['Query'],
                                  'fields' => ['*']
                              ]
                          ]
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
                     'index' => $Call['Search']['Index'],
                     'from'  => $Call['Limit']['From'],
                     'size'  => $Call['Limit']['To'],
                     'body'  =>
                     [
                          'query' =>
                          [
                              'multi_match' =>
                              [
                                  'query'  => $Call['Query'],
                                  'fuzziness' => $Call['Search']['Elastic']['Fuzziness'],
                                  'fields' => ['*']
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

        F::Log('Total hits: '.$Results['hits']['total']['value'], LOG_INFO);
        $Meta = ['Hits' => [$Call['Scope'] => $Results['hits']['total']['value']]];
        
        return ['Meta' => $Meta, 'IDs' => $IDs];
    });

    setFn('Document.Delete', function ($Call)
    {
        try
        {
            $Call = F::Run(null, 'Open', $Call);
            F::Log($Call['Link']->delete(
                 [
                     'index' => $Call['Search']['Index'],
                     'id'    => $Call['Data']['ID']
                 ]
            ), LOG_INFO);
        }
        catch (Exception $e)
        {
            F::Log('Exception: '.$e->getMessage().'. Remove data from index', LOG_ERR);
        }
        return $Call;
    });

