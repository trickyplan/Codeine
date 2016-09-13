<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
     
     setFn('Do', function ($Call)
     {
         if (is_numeric($Call['Value']))
            return number_format($Call['Value'], $Call['Digits'], ',', ' ');
         else
         {
             F::Log('Bad number format: *'.$Call['Value'].'*', LOG_WARNING);
             return $Call['Value'];
         }
     });