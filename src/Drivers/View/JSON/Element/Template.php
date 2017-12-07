<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         $Call = F::Apply('Entity', 'Load', $Call, ['Entity' => $Call['Scope']]);
         $Data = [];

         if (isset($Call['Data']))
         {
             foreach ($Call['Nodes'] as $Key => $Node)
             {
                 $Value = F::Dot($Call['Data'], $Key);
                 if (isset($Node['Visible']['JSON']) && $Node['Visible']['JSON'] && $Value !== null)
                     $Data = F::Dot($Data, $Key, $Value);
             }

             if (isset($Call['Dot']) and $Call['Dot'] !== null)
                 $Data = F::Dot($Data, $Call['Dot']);
             return $Data;
         }
         else
             return null;
     });