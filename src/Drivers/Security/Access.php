<?php

    /* Codeine
     * @author BreathLess
     * @description Access Interface 
     * @package Codeine
     * @version 7.x
     */

    setFn('Check', function ($Call)
    {
        if (is_array($Call['System']))
            foreach ($Call['System'] as $System)
                $Call = F::Run('Security.Access.'.$System, 'Check', $Call);
        else
            $Call = F::Run('Security.Access.'.$Call['System'], 'Check', $Call);

        return $Call;
    });

