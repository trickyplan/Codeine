<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::setFn('Run', function ($Call)
     {
         /*if (isset($Call['Input']['Type']))
         {
             if (isset($Call['Value'][$Call['Name']]))
             {
                     if (F::Run(array(
                             '_N' => 'Data.Type.'.$Call['Input']['Type'],
                             '_F' => 'Validate',
                             'Argument' => $Call['Input'],
                             'Value' => $Call['Value'][$Call['Name']]
                         )))
                         return $Call['Value'];
                     else
                         echo 'Type error'; //FIXME
             }
         }*/

         return $Call['Value'];
     });