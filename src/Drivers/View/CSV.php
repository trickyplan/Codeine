<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Render', function ($Call)
     {
         $CSV = [];
         foreach ($Call['Output']['Content'][0] as $Key => $Value)
             $CSV[] = '"'.$Key.'"';

         $CSV = implode(';', $CSV).PHP_EOL;
         foreach ($Call['Output']['Content'] as $Data)
         {
             $Line = [];
             foreach ($Data as $Key => $Value)
                if (is_scalar($Value))
                    $Line[] = '"'.$Value.'"';
                else
                    $Line[] = '"'.implode(',', $Value).'"';

             $CSV.= implode(';', $Line).PHP_EOL;
         }

         $Call['Output'] = $CSV;

         return $Call;
     });