<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Log.Spit.Channel.Before', function ($Call)
    {
        if (F::Dot($Call, 'Storages.'.$Call['Channel'].'.Log.AddStatistics'))
        {
            $BySeverity = [];

            foreach ($Call['Channel Logs'] as $IX => $Row)
                if (isset($BySeverity[$Row['V']]))
                    $BySeverity[$Row['V']]++;
                else
                    $BySeverity[$Row['V']] = 1;

            foreach ($Call['Levels'] as $Severity => $Label)
                if (isset($BySeverity[$Severity]))
                    $SeverityStats[] =  $Label . ': *' . $BySeverity[$Severity] . '*';

            F::Log('Total *'.count($Call['Channel Logs']).'* log messages. '
                .implode(', ', $SeverityStats)
                , LOG_NOTICE
                , $Call['Channel']
                , false
                , true);
        }
        return $Call;
    });