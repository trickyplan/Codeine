<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     setFn('Scan', function ($Call)
     {
         $Lines = explode("\n", $Call['Source']);
         $Output = array();
         $Pockets = array();

         foreach ($Lines as $Number => $Line)
             if (mb_ereg('/fixme (.+)$/SsUui', $Line, $Pockets))
                 $Output[$Number] = $Pockets[1];

         if (count($Output) == 0)
             $Output = null;
         
         return $Output;
     });