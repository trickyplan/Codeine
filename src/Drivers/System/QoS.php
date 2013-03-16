<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Start', function ($Call)
    {
        if (isset($Call['QoS']['Active']) && $Call['QoS']['Active'])
        {
            $Call = F::Run('System.QoS.'.$Call['QoS']['Method'], 'Start', $Call);

            if(isset($Call['QoS']['Classes'][$Call['QoS']['Class']]))
            {
                foreach ($Call['QoS']['Classes'][$Call['QoS']['Class']] as $Hook)
                    $Call = F::Live($Hook, $Call);
            }

            F::Log('Class selected '.$Call['QoS']['Class'], LOG_INFO);
        }
        else
        {
            F::Log('Disabled', LOG_INFO);
            $Call['QoS']['Class'] = 0;
        }


        return $Call;
    });

    setFn('Finish', function ($Call)
    {
        return F::Run('System.QoS.'.$Call['QoS']['Method'], 'Finish', $Call);
    });