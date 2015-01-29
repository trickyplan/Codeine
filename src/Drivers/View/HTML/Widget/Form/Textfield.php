<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         if (is_array($Call['Value']))
             $Call['Value'] = implode(',',$Call['Value']);

         return F::Run('View.HTML.Widget.Base', 'Make',
             $Call,
             [
                 'Tag' => 'input',
                 'Type' => (isset($Call['Subtype'])? $Call['Subtype']: 'text')
             ]);
     });