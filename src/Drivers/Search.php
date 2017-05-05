<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
    setFn('Document.Create', function ($Call)
    {
        if (isset($Call['Search']['Provider']['Available'][$Call['Provider']]))
        {
            $Provider = $Call['Search']['Provider']['Available'][$Call['Provider']];
            $Call = F::Apply($Provider['Driver'], null, $Provider, $Call);
        }

        return $Call;
    });

    setFn('Document.Delete', function ($Call)
    {
        if (isset($Call['Search']['Provider']['Available'][$Call['Provider']]))
        {
            $Provider = $Call['Search']['Provider']['Available'][$Call['Provider']];
            $Call = F::Apply($Provider['Driver'], null, $Provider, $Call);
        }

        return $Call;
    });

    setFn('Count', function ($Call)
    {
        $Call['Query'] = mb_strtolower($Call['Query']);
        
        $Call = F::Hook('beforeQuery', $Call);

        if (isset($Call['Provider']))
        {
            if (is_array($Call['Provider']))
                $Call['Search']['Provider']['Selected'] = $Call['Provider'];
            else
                $Call['Search']['Provider']['Selected'] = (array) $Call['Provider'];
        }
        else
        {
            $Call['Vertical'] = true;
            
            foreach ($Call['Search']['Provider']['Available'] as $ProviderName => $Options)
                if (F::Dot($Options, 'Vertical.Allowed'))
                    $Call['Search']['Provider']['Selected'][] = $ProviderName;
            
        } // Vertical

        $Counts = [];
       
        foreach ($Call['Search']['Provider']['Selected'] as $ProviderName)
        {
            if (mb_substr($ProviderName, 0, 1) == '-')
                ;
            else
            {
                if (isset($Call['Search']['Provider']['Available'][$ProviderName]))
                {
                    $Options = $Call['Search']['Provider']['Available'][$ProviderName];
                   
                    $Result = F::Run($Options['Driver'], 'Count', $Options, $Call);
                    
                    $Counts[$ProviderName] = $Result;
                }
            }
        }

        $Call = F::Hook('afterQuery', $Call);

        return $Counts;
    });
    
    setFn('Query', function ($Call)
    {
        $Call['Query'] = mb_strtolower($Call['Query']);
        
        $Call = F::Hook('beforeQuery', $Call);

        if (isset($Call['Provider']))
        {
            if (is_array($Call['Provider']))
                $Call['Search']['Provider']['Selected'] = $Call['Provider'];
            else
                $Call['Search']['Provider']['Selected'] = (array) $Call['Provider'];
        }
        else
        {
            $Call['Vertical'] = true;
            
            foreach ($Call['Search']['Provider']['Available'] as $ProviderName => $Options)
                if (F::Dot($Options, 'Vertical.Allowed'))
                    $Call['Search']['Provider']['Selected'][] = $ProviderName;
            
        } // Vertical

        $Call['Elements'] = [];
       
        foreach ($Call['Search']['Provider']['Selected'] as $ProviderName)
        {
            if (mb_substr($ProviderName, 0, 1) == '-')
                ;
            else
            {
                if (isset($Call['Search']['Provider']['Available'][$ProviderName]))
                {
                    $Options = $Call['Search']['Provider']['Available'][$ProviderName];
                   
                    $Result = F::Run($Options['Driver'], 'Query', $Options, $Call);
                    
                    $Call['Elements'] =  F::Merge($Call['Elements'], $Result['IDs']);
    
                    $Call['Hits'][$ProviderName] = $Result['Meta']['Hits'][$ProviderName];
                    $Call['Hits']['All'] += $Result['Meta']['Hits'][$ProviderName];
                }
            }
        }

        $Call = F::Hook('afterQuery', $Call);

        return $Call;
    });