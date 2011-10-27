<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Input', function ($Call)
     {
         foreach ($Call['Inputters'] as $Source)
             if (null !== ($Data = F::Run(array('_N' => $Call['_N'].'.'.$Source, '_F' => 'Get'))))
                 $Call['Value'] = F::Merge($Call['Value'], $Data); // Insecure

         return $Call;
     });