<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

     self::setFn('Input', function ($Call)
     {
         if (!isset($Call['Value']))
             $Call['Value'] = array();

         foreach ($Call['Sources'] as $Source)
             if (null !== ($Data = F::Run($Source['Service'], $Source['Method'],$Call)))
                 $Call['Value'] = F::Merge($Call['Value'], $Data); // Insecure

         return $Call;
     });