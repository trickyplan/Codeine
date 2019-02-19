<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Do', function ($Call)
     {
         if (F::Run('Code.Flow.Daemon', 'Running?', $Call) == true)
         {
             $Call['Output']['Content'][] =
                 [
                     'Type'  => 'Block',
                     'Class' => 'alert alert-success',
                     'Value' => '<l>Code.Flow.Daemon:Status.Started</l>'
                 ];
         }
         else
         {
             $Call['Output']['Content'][] =
                 [
                     'Type'  => 'Block',
                     'Class' => 'alert alert-danger',
                     'Value' => '<l>Code.Flow.Daemon:Status.Stopped</l>'
                 ];
         }

         $Call['Daemon'] = F::loadOptions('Code.Flow.Daemon');

         $Table = [['<l>Code.Flow.Daemon:Daemon.Title</l>', '<l>Code.Flow.Daemon:Daemon.Frequency</l>']];

         foreach ($Call['Daemon']['Daemons'] as $DaemonName => $DaemonRun)
         {
                $Table[] = [$DaemonName, (60/$DaemonRun['Frequency'])];
         }

         $Call['Output']['Content'][] =
             [
                 'Type'  => 'Table',
                 'Class' => 'table table-striped',
                 'Value' => $Table
             ];

         return $Call;
     });

    setFn('Menu', function ($Call)
    {
        return 0;
    });

    setFn('Log', function ($Call)
    {
        $Call['Output']['Content'][] =
        [
            'Type'  => 'Block',
            'Class' => 'console-inverse',
            'Value' => shell_exec('tail -n50 /var/log/codeine/daemon.log')
        ];
        
        return $Call;
    });