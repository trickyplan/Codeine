<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Filter', function ($Call)
    {
        $Call['UA'] = F::Live($Call['UA']);

        if (isset($Call['Bad UA']))
            foreach ($Call['Bad UA'] as $Bad)
                if (preg_match('/'.$Bad.'/SsUui', $Call['UA']))
                {
                    F::Log('Bad UA Detected: '.$Call['UA'].' from IP '.F::Live($Call['IP']), LOG_ERR, 'Security');
                    $Call = F::Hook('BadUADetected', $Call);
                    $Call['Skip Run'] = true;
                    break;
                }

        return $Call;
    });