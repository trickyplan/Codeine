<?php

   /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Render', function ($Call)
    {
        $Call = F::Hook('beforeJSONRender', $Call);

            $Call = F::Run('View.Pipeline', 'Do', $Call);
        
            $Call = F::Dot($Call, 'HTTP.Headers.Content-type:', 'application/json');
            $Call['Output'] = j($Call['Output']['Content']);

        $Call = F::Hook('afterJSONRender', $Call);

        return $Call;
    });