<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Apriori Parser 
     * @package Codeine
     * @version 8.x
     */

     setFn('Process', function ($Call)
     {
         if (!isset($Call['Context']) || $Call['Context'] == '')
             foreach ($Call['Parslets'] as $Parslet)
             {
                 $Tag = strtolower($Parslet);

                 $Passes = 0;

                 while (preg_match_all('@<'.$Tag.' (.+)>(.*)</'.$Tag.'>@SsUu', $Call['Output'], $Call['Parsed']))
                 {
                     $Call = F::Apply('View.JSON.Parslets.'.$Parslet, 'Parse', $Call);
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
                     $Call = F::Apply('View.JSON.Parslets.'.$Parslet, 'Parse', $Call);
                     $Passes++;

                     if ($Passes > $Call['MaxPasses'])
                     {
                         F::Log($Parslet.' Parslet raised max passes limit.', LOG_ERR);
                         break;
                     }
                 }

                 F::Log('Parslet '.$Parslet.' processed', LOG_DEBUG);
             }

         return $Call;
     });