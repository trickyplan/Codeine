<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         $Options = [];

         if (isset($Call['Value']))
            $Call['Value'] = F::Live($Call['Value']);

         $Call['Options'] = F::Live($Call['Options']);

         foreach ($Call['Options'] as $Key => $Value)
             if (is_array($Value))
                 $Options[] = F::Run ('View', 'Load', $Call,
                    [
                         'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                         'ID'    => 'Tabs/'.($Value['ID'] == $Call['Value'] ? 'Active' : 'Passive'),
                         'Data'  => $Value
                    ]);

         $Call['Value'] = implode('', $Options);

         return $Call;
     });