<?php

    /* Codeine
     * @author BreathLess
     * @description Apriori Parser 
     * @package Codeine
     * @version 7.6.2
     */

     self::setFn('Process', function ($Call)
     {
         foreach ($Call['Parslets'] as $Parslet)
         {
             $Tag = strtolower($Parslet);

             $Passes = 0;

             while (preg_match_all('/<'.$Tag.'(.*)>(.*)<\/'.$Tag.'>/SsUu', $Call['Output'], $Call['Parsed']))
             {
                 $Call['Parsed'][0] = array_unique($Call['Parsed'][0]);

                 $Call = F::Run('View.HTML.Parslets.'.$Parslet, 'Parse', $Call);
                 $Passes++;

                 if ($Passes > $Call['MaxPasses'])
                 {
                     F::Log($Parslet.' Parslet raised max passes limit.', 'Error');
                     break;
                 }
             }
         }

         return $Call;
     });