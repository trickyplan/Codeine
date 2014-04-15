<?php

    /* Codeine
     * @author BreathLess
     * @description Apriori Parser 
     * @package Codeine
     * @version 7.x
     */

     setFn('Process', function ($Call)
     {
         if (isset($Call['Output']))
             foreach ($Call['View']['HTML']['Parslets']['Queue'] as $Parslet)
             {
                 $Tag = strtolower($Parslet);

                 $Pass = 1;

                 $cTag = $Tag;

                 while($Call['Parsed'] = F::Run('Text.Regex', 'All',
                    [
                        'Pattern' => '<'.$cTag.' (.*?)>(.*?)</'.$cTag.'>',
                        'Value'   => $Call['Output']
                    ]))
                 {
                     $Call = F::Apply('View.HTML.Parslets.'.$Parslet, 'Parse', $Call);
                     $Pass++;

                     if ($Pass > 1)
                         $cTag = $Tag.$Pass;

                     if ($Pass > $Call['View']['HTML']['Parslets']['Max Passes'])
                     {
                         F::Log($Parslet.' Parslet raised max passes limit.', LOG_ERR);
                         break;
                     }
                 }

                 $Pass = 1;

                 $cTag = $Tag;

                 while($Call['Parsed'] = F::Run('Text.Regex', 'All',
                    [
                        'Pattern' => '<'.$cTag.'()>(.*?)</'.$cTag.'>',
                        'Value'   => $Call['Output']
                    ]))
                 {
                     $Call = F::Apply('View.HTML.Parslets.'.$Parslet, 'Parse', $Call);
                     $Pass++;

                     if ($Pass > 1)
                         $cTag = $Tag.$Pass;

                     if ($Pass > $Call['View']['HTML']['Parslets']['Max Passes'])
                     {
                         F::Log($Parslet.' Parslet raised max passes limit.', LOG_ERR);
                         break;
                     }
                 }

                 F::Log('Parslet '.$Parslet.' processed', LOG_DEBUG);
             }

         return $Call;
     });