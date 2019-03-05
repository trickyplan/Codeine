<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Spit', function ($Call)
    {
        $Logs = F::Logs();

        if (empty($Logs))
            ;
        else
        {
            foreach ($Logs as $Call['Channel'] => $Call['Logs'])
            {
                if (empty($Call['Logs']))
                    ;
                else
                {
                    $Call = F::Apply(null, 'Add.Timestamp', $Call);
                    $Call = F::Apply(null, 'Add.Statistics', $Call);
                    $Call = F::Apply(null, 'Add.UserString', $Call);
                }
            }
            
            $Logs = F::Logs();
        
            foreach ($Logs as $Call['Channel'] => $Call['Logs'])
            {
                $Call = F::Apply(null, 'Convert.Timestamps', $Call);

                if (F::Dot($Call, 'Log.Sorted') === true)
                    $Call['Logs'] = F::Sort($Call['Logs'], 0);
        
                F::Run('IO', 'Write', $Call,
                    [
                        'Storage' => $Call['Channel'],
                        'Where!'  => '[' . $Call['Channel'] . '] ' . $Call['HTTP']['Proto'] . $Call['HTTP']['Host'] . $Call['HTTP']['URI'],
                        'Data!'   => $Call['Logs']
                    ]);
        
                F::Run('IO', 'Close', ['Storage' => $Call['Channel']]);
            }
        }
                
        return $Call;
    });
    
    setFn('Convert.Timestamps', function ($Call)
    {
        if (F::Dot($Call, 'Channels.'.$Call['Channel'].'.Log.Timestamps.Absolute'))
            foreach ($Call['Logs'] as &$Row)
                $Row[1] = date(DATE_W3C, floor(Started)).'(+'.$Row[1].')';
        
        return $Call;
    });
    
    setFn('Add.Statistics', function ($Call)
    {
        $BySeverity = [];
        
        foreach ($Call['Logs'] as $IX => $Row)
            if (isset($BySeverity[$Row[0]]))
                $BySeverity[$Row[0]]++;
            else
                $BySeverity[$Row[0]] = 1;

        $SeverityStatistics = [];

        foreach ($Call['Levels'] as $Severity => $Label)
        {
            if (isset($BySeverity[$Severity]))
                $SeverityStatistics[$Severity] = $Label . ': *' . $BySeverity[$Severity] . '*';
        }

        F::Log($Call['Channel'] . ' Channel (' . count($Call['Logs']) . ' messages)', LOG_DEBUG, $Call['Channel']);
        
        return $Call;
    });
    
    setFn('Add.Timestamp', function ($Call)
    {
        F::Log(date(DATE_W3C, Started), LOG_DEBUG, $Call['Channel']);

        return $Call;
    });
    
    setFn('Add.UserString', function ($Call)
    {
        if (PHP_SAPI == 'cli')
        {
            $Call['Log']['User String'] = posix_getpwuid(posix_getuid())['name'].' from CLI ';

            if (empty($SSH = shell_exec('echo $SSH_CLIENT')))
                ;
            else
                $Call['Log']['User String'].= 'SSH from: '.$SSH;
        }
        else
            $Call['Log']['User String'] = '*'.$Call['HTTP']['Agent'].'* from *'.$Call['HTTP']['IP'].'*';
        
        F::Log($Call['Log']['User String'], LOG_DEBUG, $Call['Channel']);

        return $Call;
    });
    
    setFn('Autotest', function ($Call)
    {
        F::Log('Autotest. I\'m alive.', LOG_WARNING, 'All');
        return $Call;
    });

    setFn('Hook', function ($Call)
    {
        F::Log(F::Live($Call['Message'], $Call), $Call['Verbose'], $Call['Channel']);
        return $Call;
    });
