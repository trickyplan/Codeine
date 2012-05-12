<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Load', function ($Call)
     {
         $Call['Layout'] = F::Run ('View', 'Load', array('Scope' => 'Default'), $Call);

/*         if (isset($Call['Zone']))
            $Call['Layout'] = str_replace('<place>Content</place>',
                F::Run ('View', 'Load', array('Scope' => $Call['Zone'], 'ID' => 'Zone'), $Call),
                $Call['Layout']);*/

         return $Call;
     });