<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Scan', function ($Call)
     {
         $Lines = explode("\n", $Call['Source']);
         $Output = array();
         $Pockets = array();

         foreach ($Lines as $Number => $Line)
             if (preg_match('/fixme (.+)$/SsUui', $Line, $Pockets))
                 $Output[$Number] = $Pockets[1];

         if (count($Output) == 0)
             $Output = null;
         
         return $Output;
     });