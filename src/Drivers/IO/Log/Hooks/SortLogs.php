<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeLogSpit', function ($Call)
    {
        if (F::Dot($Call, 'Log.Sorted') === true)
            $Call['Channel Logs'] = F::Sort($Call['Channel Logs'], 0);
        
        return $Call;
    });