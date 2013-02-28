<?php

    /* Codeine
     * @author BreathLess
     * @description Apriori Parser 
     * @package Codeine
     * @version 7.x
     */

     setFn('Process', function ($Call)
     {
         foreach ($Call['Parslets'] as $Parslet)
         {
             $Tag = strtolower($Parslet);

             $Passes = 0;

             while (preg_match_all('@<'.$Tag.' (.+)>(.*)</'.$Tag.'>@SsUu', $Call['Output'], $Call['Parsed']))
             {
                 $Call = F::Run('View.HTML.Parslets.'.$Parslet, 'Parse', $Call);
                 $Passes++;

                 if ($Passes > $Call['MaxPasses'])
                 {
                     F::Log($Parslet.' Parslet raised max passes limit.', LOG_ERR);
                     break;
                 }
             }

             $Passes = 0;

             while (preg_match_all('@<'.$Tag.'()>(.*)</'.$Tag.'>@SsUu', $Call['Output'], $Call['Parsed']))
             {
                 $Call = F::Run('View.HTML.Parslets.'.$Parslet, 'Parse', $Call);
                 $Passes++;

                 if ($Passes > $Call['MaxPasses'])
                 {
                     F::Log($Parslet.' Parslet raised max passes limit.', LOG_ERR);
                     break;
                 }
             }
         }

         return $Call;
     });