<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

     setFn('Do', function ($Call)
     {
         $Call = F::Hook('beforeSetDo', $Call);

             $Call = F::Run('Entity', 'Load', $Call);

             if (isset($Call['Nodes'][$Call['Key']]['Widgets']['Write']) or isset($Call['Nodes'][$Call['Key']]['Widgets']['Update']) or isset($Call['Nodes'][$Call['Key']]['User Writable']))
             {
                 $Data = F::Run('Entity', 'Read', $Call,
                 [
                     'One'      => true
                 ]);

                 $Data = F::Dot($Data, $Call['Key'], $Call['Value']);

                 F::Run('Entity', 'Update', $Call,
                     [
                         'Data'   => $Data
                     ]);

                 $Call['Output']['Content'][] = 'OK';
             }

         $Call = F::Hook('afterSetDo', $Call);

         return $Call;
     });

     setFn('All', function ($Call)
     {
         $Entities = F::Run('Entity', 'Read', $Call);

         $Call = F::Apply('Code.Progress', 'Start', $Call);

             $Call['Progress']['Max'] = count($Entities);

             foreach ($Entities as $Entity)
             {
                 $Call['Progress']['Now']++;
                 F::Run('Entity', 'Update',
                     [
                         'Entity' => $Call['Entity'],
                         'Where'  => $Entity['ID'],
                         'Live Fields' => [$Call['Field']]
                     ]);
                 $Call = F::Apply('Code.Progress', 'Log', $Call);
             }

         $Call = F::Apply('Code.Progress', 'Finish', $Call);

         return $Call;
     });