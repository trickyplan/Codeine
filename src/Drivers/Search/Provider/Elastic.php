<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    require Root.'/vendor/autoload.php';

    setFn('Open', function ($Call)
    {
        $Call['Link'] = new Elasticsearch\Client($Call['Elastic Search']['Options']);
        return $Call;
    });

    setFn('Add', function ($Call)
    {
        $Call = F::Run(null, 'Open', $Call);

        try
        {
            F::Log($Call['Link']->index(
                 [
                     'index' => $Call['Index'],
                     'id'    => $Call['Data']['ID'],
                     'type'  => $Call['Type'],
                     'body'  => $Call['Data']
                 ]
            ), LOG_INFO);
        }
        catch (Exception $e)
        {
            F::Log($e->getMessage(), LOG_ERR);
        }

        return $Call;
    });

    setFn('Query', function ($Call)
    {
        try
        {
            $Call = F::Run(null, 'Open', $Call);
            $Call['Query'] = mb_substr($Call['Query'], 0, 32);

            F::Log('Start search on query: '.$Call['Query'], LOG_NOTICE);

            $Query = [
                     'index' => $Call['Index'],
                     'type'  => $Call['Type'],
/*                     'from'  => $Call['EPP']*($Call['Page']-1),
                     'size'  => $Call['EPP'],*/
                     'body'  =>
                     [
                          'query' =>
                          [
                              'multi_match' =>
                              [
                                  'query' => $Call['Query'],
                                  'fields' => $Call['Search fields'],
                                  'operator' => 'and'
                              ]
                          ],
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

        }
        catch (Exception $e)
        {
            F::Log($e->getMessage(), LOG_CRIT, 'Developer');
            $Results = ['hits' => ['total' => 0]];
        }

        $IX = 0;
        $SERP = [];

        F::Log('Total hits: '.$Results['hits']['total'], LOG_INFO);

        if ($Results['hits']['total'] > 0)
        {
            foreach ($Results['hits']['hits'] as $Hit)
            {
                $Hit['_source']['Scope'] = $Call['Scope'];

                $Data = F::Run('Entity', 'Read',
                               [
                                   'Entity' => $Call['Scope'],
                                   'One' => true,
                                   'Fields' => $Call['Show fields'],
                                   'Where' => $Hit['_id']
                               ]
                );

                if (empty($Data))
                    ;
                else
                {
                    $IX++;

                    if (isset($Call['Default']))
                        $Data = F::Merge($Call['Default'], $Data);

                    $Data['Snippet'] = isset($Hit['highlight'][$Call['Highlight']][0])? $Hit['highlight'][$Call['Highlight']][0]: '';
                    $Data['Provider'] = '<l>'.$Call['Scope'].'.Control:Title</l>';
                    // FIXME

                    $SERP[$Hit['_id']] =
                    [
                        'Score' => $Hit['_score'],
                        'Type'  => 'Template',
                        'Scope' => $Call['Scope'],
                        'ID'    => 'Show/Search',
                        'Data'  => $Data
                    ];
                }
            }

            $Results['hits']['total'] = $IX;
        }
        else
        {
            $SERP = null;
        }

        $Meta = ['Hits' => [$Call['Scope'] => $Results['hits']['total']]];

        return ['Meta' => $Meta, 'SERP' => $SERP];
    });

    setFn('Remove', function ($Call)
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

