<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

     self::Fn('Fix', function ($Call)
     {
         F::Run(array(
            'Object' => array('Node.Set', $Call['Call']['Entity']),
            'ID' => $Call['Call']['ID'],
            'Key' => 'Role:'.$Call['Call']['Role'],
            'Value' => 'Gravatar' // FIXME
         ));

         return 'Application Run Failed. Fallback to default';
     });