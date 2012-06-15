<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Make', function ($Call)
     {
         $Options = array();

         if (isset($Call['Value']))
            $Call['Value'] = F::Live($Call['Value']);
         else
            $Call['Value'] = array();

         $Call['Options'] = F::Live($Call['Options']);

         foreach ($Call['Options'] as $Key => $Value)
             if (is_array($Value))
                 $Options[] = F::Run ('View', 'LoadParsed', $Call,
                    array(
                         'Scope' => isset($Call['Scope'])? $Call['Scope']: 'Default',
                         'ID'    => 'UI/HTML/Navlist/'.($Value['ID'] == $Call['Value'] ? 'Active' : 'Passive'),
                         'Data'  => $Value
                    ));
             else
                $Options[] = F::Run ('View', 'LoadParsed', $Call,
                    array(
                         'Scope' => isset($Call['Scope'])? $Call['Scope']: 'Default',
                         'ID'    => 'UI/HTML/Navlist/Header',
                         'Data'  => array('Title' => $Value)
                    ));

         $Call['Value'] = implode('', $Options);

         return F::Run ('View', 'LoadParsed', $Call,
                        array(
                             'Scope' => isset($Call['Scope'])? $Call['Scope']: 'Default',
                             'ID'    => 'UI/HTML/'.(isset($Call['Template'])? $Call['Template'] : 'Navlist'),
                             'Data'  => $Call
                        ));
     });