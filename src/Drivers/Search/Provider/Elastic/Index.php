<?php

/* Codeine
 * @author bergstein@trickyplan.com
 * @description  
 * @package Codeine
 * @version 2019.x
 */

    setFn('Create', function ($Call)
    {
        $Link = Elasticsearch\ClientBuilder::create()->build();
        $Options = F::Dot($Call,'Search.Elastic.Index.Create.Default');

        $Options['index'] = $Call['Search']['Index'];
        $Options['body']['mappings']['properties'] = $Call['Search']['Properties'];

        F::Log($Options, LOG_INFO);
        // Create the index with mappings and settings now
        $Response = $Link->indices()->create($Options);
        return $Response;
    });

    setFn('Delete', function ($Call)
    {
        $Link = Elasticsearch\ClientBuilder::create()->build();

        $Options =
        [
            'index' => $Call['Search']['Index']
        ];

        if ($Link->indices()->exists($Options))
            $Response = $Link->indices()->delete($Options);
        else
            $Response = null;

        return $Response;
    });

    setFn('Create.From.Entity', function ($Call)
    {
        $Call = F::loadOptions($Call['Entity'].'.Entity', null, $Call);
        $Call['Search']['Index'] = mb_strtolower($Call['Entity']);

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (F::Dot($Node, 'Search.Allowed'))
            {
                $Call['Search']['Properties'][$Name] =
                    [
                        'type' => 'text',
                        'analyzer' => 'english'
                    ];
            }
        }
        F::Run(null, 'Delete', $Call);
        $Response = F::Run(null, 'Create', $Call);

        return $Call;
    });