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
                 $Options[] = F::Run ('View', 'LoadParsed', $Call,
                    array(
                         'Scope' => isset($Call['Scope'])? $Call['Scope']: 'Default',
                         'ID'    => 'UI/Navlist/'.($Value['ID'] == $Call['Value'] ? 'Active' : 'Passive'),
                         'Data'  => $Value
                    ));
             else
                $Options[] = F::Run ('View', 'LoadParsed', $Call,
                    array(
                         'Scope' => isset($Call['Scope'])? $Call['Scope']: 'Default',
                         'ID'    => 'UI/Navlist/Header',
                         'Data'  => array('Title' => $Value)
                    ));

         $Call['Value'] = implode('', $Options);

         return F::Run ('View', 'LoadParsed', $Call,
                        array(
                             'Scope' => isset($Call['Scope'])? $Call['Scope']: 'Default',
                             'ID'    => 'UI/'.(isset($Call['Template'])? $Call['Template'] : 'Navlist'),
                             'Data'  => $Call
                        ));
     });