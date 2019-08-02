<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        if ($Duration = F::Dot($Call, 'Run.Call.Behaviours.Postponed.DurationMicroseconds'))
        {
            F::Log('Postponed for '.$Duration.' microseconds', LOG_WARNING);
            usleep($Duration);
        }

        $Call['Run']['Result'] = F::Run($Call['Run']['Service'], $Call['Run']['Method'], $Call['Run']['Call'], ['Behaviours!' => $Call['Behaviours']]);

        return $Call;
    });