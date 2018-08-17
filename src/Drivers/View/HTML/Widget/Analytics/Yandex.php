<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Make', function ($Call)
    {
        $Call['ID'] = isset($Call['ID'])? $Call['ID']: F::Dot($Call, 'Analytics.Yandex.ID');
        $Code = '';

        if (F::Dot($Call, 'Analytics.Yandex.DoNotTrack') && F::Run('System.Interface.HTTP.DNT', 'Detect', $Call))
        {
            $Code = '<!-- Do Not Track enabled. Yandex Metrika supressed. -->';
            F::Log('GA Suppressed by DNT: '.$Call['ID'], LOG_INFO, 'Marketing');
        }
        else
        {
            if (in_array($Call['HTTP']['URL'], F::Dot($Call, 'Analytics.Yandex.URLs.Disabled')))
                F::Log('GA Suppressed by URLs: '.$Call['ID'], LOG_INFO, 'Marketing');
            else
            {
                if (F::Dot($Call, 'Analytics.Yandex.Environment.'.F::Environment()) === true)
                {
                    $Code = F::Live(F::Run('View', 'Load', $Call,
                        [
                            'Scope'     => 'View.HTML.Widget.Analytics',
                            'ID'        => 'Yandex'
                        ]), $Call);
                    
                    F::Log('YM Registered: '.$Call['ID'], LOG_INFO, 'Marketing');
                }
                else
                    F::Log('YMSuppressed by Environment: '.$Call['ID'], LOG_INFO, 'Marketing');
            }
        }

        return $Code;
     });