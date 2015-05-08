<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
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