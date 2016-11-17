<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Run', function ($Call)
     {
         $Call['Parallel']['Threads'] = F::Live($Call['Parallel']['Threads']);
         F::Log('Threads Count: '.$Call['Parallel']['Threads'], LOG_NOTICE);

         $Call['Data Size'] = count($Call['Data']);
         F::Log('Data size: '.$Call['Data Size'], LOG_NOTICE);
         $Call['Chunk Size'] = ceil($Call['Data Size'] / $Call['Parallel']['Threads']);
         F::Log('Chunk size: '.$Call['Chunk Size'], LOG_NOTICE);
         $Call['Data'] = array_chunk($Call['Data'], $Call['Chunk Size'], true);

         foreach ($Call['Data'] as $Index => $Chunk)
         {
             $Ungrateful = [];
             $PID = pcntl_fork();

             if ($PID == -1)
             {
                 F::Log('Parallel: Fork failed', LOG_CRIT);
                 return -1;
             } //TODO: ошибка - не смогли создать процесс
             elseif ($PID)
             {
                 $Ungrateful[$PID] = true;
                 F::Log('Child forked '.$PID, LOG_INFO);
             }
             else
             {
                 F::Live($Call['Run'], $Call, [$Call['Key'] => $Chunk]);
                 exit(0);
             }
         }
     });