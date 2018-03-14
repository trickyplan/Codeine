<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        return F::Run('IO.Storage.Redis', 'Open', $Call);
    });

    setFn('Read', function ($Call)
    {
        $Result = [];

        if (isset($Call['Limit']))
            $Iterations = $Call['Limit']['To']-$Call['Limit']['From'];
        else
            $Iterations = 1;
        
        $Count = F::Run(null, 'Count', $Call);
        
        if ($Count < $Iterations)
            $Iterations = $Count;
            
        F::Log('Pull from *'.$Call['Scope'].$Call['Queue'].'* queue', LOG_INFO, 'Administrator');
        for ($IX = 0; $IX < $Iterations; $IX++)
            $Result[$IX] = $Call['Link']->lPop($Call['Scope'].$Call['Queue']);
        
        return $Result;
    });

    setFn('Write', function ($Call)
    {
        F::Log('Push to *'.$Call['Scope'].$Call['Queue'].'* queue', LOG_INFO, 'Administrator');
        return $Call['Link']->rPush($Call['Scope'].$Call['Queue'], $Call['Data']);
    });

    setFn('Count', function ($Call)
    {
        F::Log('Count: '.$Call['Scope'].$Call['Queue'], LOG_INFO, 'Administrator');
        return $Call['Link']->lLen($Call['Scope'].$Call['Queue']);
    });
