<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeLogSpit', function ($Call)
    {
        $BySeverity = [];
        
        foreach ($Call['Channel Logs'] as $IX => $Row)
            if (isset($BySeverity[$Row['V']]))
                $BySeverity[$Row['V']]++;
            else
                $BySeverity[$Row['V']] = 1;

        $SeverityStatistics = [];

        foreach ($Call['Levels'] as $Severity => $Label)
        {
            if (isset($BySeverity[$Severity]))
                $SeverityStatistics[$Severity] = $Label . ': *' . $BySeverity[$Severity] . '*';
        }

        F::Log($Call['Channel'] . ' Channel (' . count($Call['Channel Logs']) . ' messages)', LOG_DEBUG, $Call['Channel']);
        
        return $Call;
    });