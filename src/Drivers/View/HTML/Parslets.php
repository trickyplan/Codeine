<?php

    /* Codeine
     * @author BreathLess
     * @description Apriori Parser 
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Process', function ($Call)
     {
         foreach ($Call['Parslets'] as $Parslet)
         {
             $Tag = strtolower($Parslet);

             if (preg_match_all('/<'.$Tag.'>(.*)<\/'.$Tag.'>/Uus', $Call[$Call['Var']], $Call['Parsed']))
                 $Call[$Call['Var']] = F::Run('View.HTML.Parslets.'.$Parslet, 'Parse', $Call);
         }

         return $Call;
     });