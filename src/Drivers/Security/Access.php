<?php

    /* Codeine
     * @author BreathLess
     * @description Access Interface 
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        if (isset($Call['System']))
        {
            $Call['System'] = (array) $Call['System'];

            foreach ($Call['System'] as $System)
                $Call = F::Apply('Security.Access.'.$System, 'Check', $Call);
        }

        return $Call['Decision'];
    });

