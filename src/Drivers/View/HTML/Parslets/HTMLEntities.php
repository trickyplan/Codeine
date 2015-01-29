<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */

     setFn('Parse', function ($Call)
     {
         foreach ($Call['Parsed'][2] as $Ix => $Match)
         {
             $Call['Output'] = str_replace ($Call['Parsed'][0][$Ix],
                 htmlentities($Match)
                 ,$Call['Output']);
         }

        return $Call;
     });