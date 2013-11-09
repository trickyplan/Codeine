<?php

    /* Codeine
     * @author BreathLess
     * @description Apriori Parser 
     * @package Codeine
     * @version 7.x
     */

     setFn('Process', function ($Call)
     {
         if (!isset($Call['Context']) || $Call['Context'] == '' && isset($Call['Output']))
             foreach ($Call['Parslets'] as $Parslet)
             {
                 $Tag = strtolower($Parslet);

                 $Pass = 1;

                 $cTag = $Tag;

                 while (preg_match_all('@<'.$cTag.' (.+)>(.*)</'.$cTag.'>@SsUu', $Call['Output'], $Call['Parsed']))
                 {
                     $Call = F::Apply('View.HTML.Parslets.'.$Parslet, 'Parse', $Call);
                     $Pass++;

                     if ($Pass > 1)
                         $cTag = $Tag.$Pass;

                     if ($Pass > $Call['MaxPasses'])
                     {
                         F::Log($Parslet.' Parslet raised max passes limit.', LOG_ERR);
                         break;
                     }
                 }

                 $Pass = 1;

                 $cTag = $Tag;

                 while (preg_match_all('@<'.$cTag.'()>(.*)</'.$cTag.'>@SsUu',
                     $Call['Output'], $Call['Parsed']))
                 {
                     $Call = F::Apply('View.HTML.Parslets.'.$Parslet, 'Parse', $Call);
                     $Pass++;

                     if ($Pass > 1)
                         $cTag = $Tag.$Pass;

                     if ($Pass > $Call['MaxPasses'])
                     {
                         F::Log($Parslet.' Parslet raised max passes limit.', LOG_ERR);
                         break;
                     }
                 }

                 F::Log('Parslet '.$Parslet.' processed', LOG_DEBUG);
             }

         return $Call;
     });