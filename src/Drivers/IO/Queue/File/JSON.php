<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     * @date 13.08.11
     * @time 22:37
     */

    setFn ('Open', function ($Call)
    {
        if (isset($Call['Scope']))
        {
            $Call['Link']['Path'] = $Call['Directory'].DS.$Call['Scope'].DS.$Call['Filename'];
            if (file_exists($Call['Directory'].DS.$Call['Scope']))
                ;
            else
                mkdir ($Call['Directory'].DS.$Call['Scope']);
        }
        else
            $Call['Link']['Path'] = $Call['Directory'].DS.$Call['Filename'];
        
        if (F::file_exists($Call['Link']['Path']))
            $Call['Link']['Data'] = (array) jd(file_get_contents($Call['Link']['Path']), true);
        else
            $Call['Link']['Data'] = [];

        return $Call['Link'];
    });

    setFn ('Read', function ($Call)
    {
        $Call['Link'] = F::Run(null, 'Open', $Call);
        
        $Result = [];
        
        $Count = F::Run(null, 'Count', $Call);
        
        if (isset($Call['Limit']))
            $Iterations = $Call['Limit']['To']-$Call['Limit']['From'];
        else
            $Iterations = 1;
        
        if ($Count < $Iterations)
            $Iterations = $Count;
        
        for ($IX = 0; $IX < $Iterations; $IX++)
            $Result[$IX] = array_shift($Call['Link']['Data']);
        
        file_put_contents($Call['Link']['Path'], j($Call['Link']['Data']));

        return $Result;
    });

    setFn ('Write', function ($Call)
    {
        $Call['Link'] = F::Run(null, 'Open', $Call);
        array_push($Call['Link']['Data'], $Call['Data']);
        return file_put_contents($Call['Link']['Path'], j($Call['Link']['Data']));
    });

    setFn ('Count', function ($Call)
    {
        return count($Call['Link']['Data']);
    });

    setFn ('Close', function ($Call)
    {
        return true;
    });