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

         $Call['Options'] = F::Live($Call['Options']);

         foreach ($Call['Options'] as $Key => $Value)
             if (is_array($Value))
                 $Options[] = F::Run ('View', 'Load', $Call,
                    [
                         'Scope' => $Call['Widget Set'].'/Widgets',
                         'ID'    => 'Navlist/'.($Value['ID'] == $Call['Value'] ? 'Active' : 'Passive'),
                         'Data'  => $Value
                    ]);
             else
                $Options[] = F::Run ('View', 'Load', $Call,
                    [
                         'Scope' => $Call['Widget Set'].'/Widgets',
                         'ID'    => 'Navlist/Header',
                         'Data'  => array('Title' => $Value)
                    ]);

         $Call['Value'] = implode('', $Options);

         return $Call;
     });