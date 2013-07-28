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
            $Call['Value'] = $_SERVER['REQUEST_URI']; // FIXME

         $Call['Options'] = F::Live($Call['Options']);

         foreach ($Call['Options'] as $Key => $Value)
             if (is_array($Value))
                 $Options[] = F::Run ('View', 'Load', $Call,
                    array(
                         'Scope' => isset($Call['Scope'])? $Call['Scope']: 'Default',
                         'ID'    => 'UI/Navbar/'.($Value['URL'] == $Call['Value'] ? 'Active' : 'Passive'),
                         'Data'  => $Value
                    ));
             else
                $Options[] = F::Run ('View', 'Load', $Call,
                    array(
                         'Scope' => isset($Call['Scope'])? $Call['Scope']: 'Default',
                         'ID'    => 'UI/Navbar/Header',
                         'Data'  => array('Title' => $Value)
                    ));

         $Call['Value'] = implode('', $Options);

         return F::Run ('View', 'Load', $Call,
                        array(
                             'Scope' => isset($Call['Scope'])? $Call['Scope']: 'Default',
                             'ID'    => 'UI/'.(isset($Call['Template'])? $Call['Template'] : 'Navbar'),
                             'Data'  => $Call
                        ));
     });