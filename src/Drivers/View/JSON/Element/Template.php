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
                 if (isset($Node['Visible']['JSON']) && $Node['Visible']['JSON'])
                     $Data = F::Dot($Data, $Key, F::Dot($Call['Data'], $Key));

             return $Data;
         }
         else
             return null;
     });