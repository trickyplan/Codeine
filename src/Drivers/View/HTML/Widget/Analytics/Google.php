<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Universal', function ($Call)
    {
        // FIXME GA Options
        $Code = '';

        if (F::Dot($Call, 'Analytics.Google.DoNotTrack') && F::Run('System.Interface.HTTP.DNT', 'Detect', $Call))
        {
            $Code = '<!-- Do Not Track enabled. Google Analytics supressed. -->';
            F::Log('GA Suppressed by DNT: '.$Call['ID'], LOG_INFO, 'Marketing');
        }
        else
        {
            if (in_array($Call['HTTP']['URL'], F::Dot($Call, 'Analytics.Google.URLs.Disabled')))
                F::Log('GA Suppressed by URLs: '.$Call['ID'], LOG_INFO, 'Marketing');
            else
            {
                if (F::Dot($Call, 'Analytics.Google.Environment.'.F::Environment()) === true)
                {
                    $Code = F::Live(F::Run('View', 'Load', $Call,
                        [
                            'Scope'     => 'View.HTML.Widget.Analytics',
                            'ID'        => 'Google'
                        ]), $Call);
                    
                    F::Log('GA Registered: '.$Call['ID'], LOG_INFO, 'Marketing');
                }
                else
                    F::Log('GA Suppressed by Environment: '.$Call['ID'], LOG_INFO, 'Marketing');
            }
        }

        return $Code;
    });