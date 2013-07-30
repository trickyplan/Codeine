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
            $Call['Value'] = $Call['URL']; // FIXME

         $Call['Options'] = F::Live($Call['Options']);

         foreach ($Call['Options'] as $Key => $Value)
             if (is_array($Value))
                 $Options[] = F::Run ('View', 'Load', $Call,
                    [
                         'Scope' => $Call['Widget Set'].'/Widgets',
                         'ID'    => 'Navbar/'.($Value['URL'] == $Call['Value'] ? 'Active' : 'Passive'),
                         'Data'  => $Value
                    ]);
             else
                $Options[] = F::Run ('View', 'Load', $Call,
                    array(
                        'Scope' => $Call['Widget Set'].'/Widgets',
                         'ID'    => 'Navbar/Header',
                         'Data'  =>
                         [
                             'Title' => $Value
                         ]
                    ));

         $Call['Value'] = implode('', $Options);

         return $Call;
     });