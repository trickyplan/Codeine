<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

     setFn('Do', function ($Call)
     {
         $Call = F::Hook('beforeIncreaseDo', $Call);

             $Call = F::Run('Entity', 'Load', $Call);

             if (isset($Call['Nodes'][$Call['Key']]['Widgets']['Write']) or isset($Call['Nodes'][$Call['Key']]['Widgets']['Update']))
             {
                 $Data = F::Run('Entity', 'Read', $Call,
                 [
                     'One'      => true
                 ]);

                 $Data = F::Dot($Data, $Call['Key'], F::Dot($Data, $Call['Key']) + $Call['Value']);

                 F::Run('Entity', 'Update', $Call,
                     [
                         'Data'   => $Data
                     ]);

                 $Call['Output']['Content'][] = 'OK';
             }

         $Call = F::Hook('afterIncreaseDo', $Call);

         return $Call;
     });