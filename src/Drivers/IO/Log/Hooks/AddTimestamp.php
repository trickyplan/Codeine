<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeLogSpit', function ($Call)
    {
        F::Log(date(DATE_W3C, Started), LOG_DEBUG, $Call['Channel']);
        return $Call;
    });