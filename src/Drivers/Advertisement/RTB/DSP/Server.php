<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Response', function ($Call)
    {
        $Call = F::Hook('beforeRTBResponse', $Call);

            $Call['Output']['Content'] = $Call['RTB']['DSP']['Response'];

        $Call = F::Hook('afterRTBResponse', $Call);
        
        return $Call;
    });