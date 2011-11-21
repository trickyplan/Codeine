<?php

    /* Codeine
     * @author BreathLess
     * @description Apriori Parser 
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Process', function ($Call)
     {
         foreach ($Call['Parslets'] as $Parslet)
         {
             $Tag = strtolower($Parslet);

             if (preg_match_all('@<'.$Tag.'>(.*)<\/'.$Tag.'>@SsUu', $Call['Output'], $Parsed))
             {
                 $Call['Output'] = F::Run($Call,
                    array(
                        '_N' => $Call['_N'].'.'.$Parslet,
                        '_F' => 'Parse',
                        'Parsed' => $Parsed
                    )
                 );
             }
         }

         return $Call;
     });