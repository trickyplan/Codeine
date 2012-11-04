<?php

    /* Codeine
     * @author BreathLess
     * @description: Event Driver
     * @package Codeine
     * @version 7.x
     * @date 29.07.21
     * @time 22:13
     */

    setFn('Open', function ($Call)
    {
        return true;
    });

    setFn('Send', function ($Call)
    {
        // FIXME Options / Strategy
        $Map = &$Call['Events'];

        if (isset($Map[$Call['Message']]))
        {
            $Results = array();
            
            foreach ($Map[$Call['Message']] as $Name => $Hook)
                $Results[$Name] = F::Run($Call,$Hook);

            return $Results['Result'];
        }
        else
            return null;
    });
