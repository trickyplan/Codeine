<?php

    /* Codeine
     * @author BreathLess
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

    setFn('Index', function ($Call)
    {
        $Call = F::Run(null, 'Open', $Call);

        try
        {
            F::Log($Call['Link']->index(
                 [
                     'index' => 'project',
                     'id'    => $Call['Data']['ID'],
                     'type'  => $Call['Type'],
                     'body'  => $Call['Data']
                 ]
            ), LOG_INFO);
        }
        catch (Exception $e)
        {
            
        }

        return $Call;
    });

    setFn('Query', function ($Call)
    {
        try
        {
            $Call = F::Run(null, 'Open', $Call);
            $Call['Query'] = mb_substr($Call['Query'], 0, 32);

            $Results = $Call['Link']->search(
                 [
                     'index' => 'project',
                     'type'  => $Call['Type'],
                     'size'  => $Call['EPP'],
                     'from'  => $Call['EPP']*($Call['Page']-1),
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
                 ]);
        }
        catch (Exception $e)
        {
            F::Log($e->getMessage(), LOG_CRIT, 'Developer');
            $Results = ['hits' => ['total' => 0]];
        }

        $IX = 0;
        $SERP = [];

        if ($Results['hits']['total'] > 0)
        {
            foreach ($Results['hits']['hits'] as $Hit)
            {
                $Hit['_source']['Scope'] = $Call['Scope'];

                $Data = F::Run('Entity', 'Read', ['Entity' => $Call['Scope'], 'One' => true, 'Where' => $Hit['_id']]);

                if (empty($Data))
                    ;
                else
                {
                    $IX++;
                    $Data['Snippet'] = isset($Hit['highlight'][$Call['Highlight']][0])? $Hit['highlight'][$Call['Highlight']][0]: '';

                    $SERP[$Hit['_id']] =
                    [
                        'Score' => $Hit['_score'],
                        'Type'  => 'Template',
                        'Scope' => $Call['Scope'].'/Show',
                        'ID'    => isset($Call['Search Template'])? $Call['Search Template'] : 'Search',
                        'Data'  => $Data
                    ];
                }
            }

            $Results['hits']['total'] = $IX;
        }
        else
        {
            $SERP[$Call['Scope']] =
                [
                    'Type'  => 'Template',
                    'Scope' => $Call['Scope'].'/Search',
                    'ID'    => 'Empty'
                ];
        }

        $Meta = ['Hits' => [$Call['Scope'] => $Results['hits']['total']]];
//d(__FILE__, __LINE__, $Results);
        return ['Meta' => $Meta, 'SERP' => $SERP];
    });

    setFn('Remove', function ($Call)
    {
        $Call['Query'] = mb_substr($Call['Query'], 0, 32);
        
        try
        {
            F::Log($Call['Link']->delete(
                 [
                     'index' => 'project',
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

