<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         if (isset($Call['Value']))
         {
             if (is_array($Call['Value']))
                 $Call['Value'] = implode(',', $Call['Value']);

             $Call['HValue'] = $Call['Value'];

             $Entity = F::Run('Entity', 'Read', $Call,
                 [
                    'Entity' => $Call['Entity'],
                    'Fields' => ['Title'],
                    'Where' => $Call['Value'],
                    'One' => true
                 ]);
             
             $Call['Value'] = htmlspecialchars($Entity['Title']);
         }
         else
             $Call['Value'] = '';
         
         return F::Run('View.HTML.Widget.Base', 'Make',
             $Call,
             [
                 'Tag' => 'input',
                 'Type' => (isset($Call['Subtype'])? $Call['Subtype']: 'text')
             ]);
     });