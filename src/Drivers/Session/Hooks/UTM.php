<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('afterSessionInitialize', function ($Call)
    {
        F::Log('UTM Detection', LOG_DEBUG, 'Security');
        
        if (isset($Call['SID']))
        {
            if (isset($Call['Request']['utm']['source'])
                or isset($Call['Request']['utm']['medium'])
                or isset($Call['Request']['utm']['campaign']))
            {
                F::Log('UTM Detected', LOG_INFO, 'Security');
                $Call = F::Apply('Session', 'Write', $Call);
            }
        }
        
        return $Call;
    });
    
    setFn('Get Source', function ($Call)
    {
        if (isset($Call['Request']['utm']['source']))
            $Source = $Call['Request']['utm']['source'];
        else
            $Source = F::Dot($Call['Data'], $Call['Name']);
        
        return $Source;
    });
    
    setFn('Get Medium', function ($Call)
    {
        if (isset($Call['Request']['utm']['medium']))
            $Medium = $Call['Request']['utm']['medium'];
        else
            $Medium = F::Dot($Call['Data'], $Call['Name']);
        
        return $Medium;
    });
    
    setFn('Get Campaign', function ($Call)
    {
        if (isset($Call['Request']['utm']['campaign']))
            $Campaign = $Call['Request']['utm']['campaign'];
        else
            $Campaign = F::Dot($Call['Data'], $Call['Name']);
        
        return $Campaign;
    });
    
    setFn('Get Term', function ($Call)
    {
        if (isset($Call['Request']['utm']['term']))
            $Term = $Call['Request']['utm']['term'];
        else
            $Term = F::Dot($Call['Data'], $Call['Name']);
        
        return $Term;
    });
    
    setFn('Get Content', function ($Call)
    {
        if (isset($Call['Request']['utm']['content']))
            $Content = $Call['Request']['utm']['content'];
        else
            $Content = F::Dot($Call['Data'], $Call['Name']);
        
        return $Content;
    });
    
    