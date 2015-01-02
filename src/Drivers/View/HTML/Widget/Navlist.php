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

         $NavlistScope = isset($Call['Scope'])? $Call['Scope']: 'Navlist';

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
                         'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                         'ID'    => $NavlistScope.'/Element',
                         'Data'  => $Value
                     ]);
             }
             else
                 $Options[] = F::Run ('View', 'Load', $Call,
                     [
                         'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                         'ID'    => $NavlistScope.'/Header',
                         'Data'  => ['Title' => $Value]
                     ]);
         }

         $Call['Value'] = implode('', $Options);

         return $Call;
     });