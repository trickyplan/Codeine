<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         $Options = array();

         if (isset($Call['Value']))
            $Call['Value'] = F::Live($Call['Value']);
         else
            $Call['Value'] = 0;

         $Call['Options'] = F::Live($Call['Options']);

         foreach ($Call['Options'] as $Key => $Value)
         {
             if (is_array($Value))
             {
                 $Options[] = F::Run ('View', 'Load', $Call,
                     [
                         'Scope' => $Call['Widget Set'].'/Widgets',
                         'ID'    => 'Navlist/Element',
                         'Data'  => $Value
                     ]);
             }
             else
                 $Options[] = F::Run ('View', 'Load', $Call,
                     [
                         'Scope' => $Call['Widget Set'].'/Widgets',
                         'ID'    => 'Navlist/Header',
                         'Data'  => array('Title' => $Value)
                     ]);
         }

         $Call['Value'] = implode('', $Options);

         return $Call;
     });