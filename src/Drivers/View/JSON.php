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

            if ((bool) F::Dot($Call, 'View.JSON.Pipeline.Enabled'))
                $Call = F::Run('View.Pipeline', 'Do', $Call);
            else
            {
                $Call['Output']['Content'] = $Call['Output'];
                F::Log('JSON Pipeline is disabled', LOG_INFO);
            }
        
            $Call = F::Dot($Call, 'HTTP.Headers.Content-Type:', 'application/json');

            if ($Key = F::Dot($Call, 'View.JSON.Key'))
                $Call['Output'] = j(F::Dot($Call['Output'], $Key));
            else
                $Call['Output'] = j(F::Dot($Call['Output'], 'Content'));
            
        $Call = F::Hook('afterJSONRender', $Call);

        return $Call;
    });