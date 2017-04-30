<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeLocaleDetect', function ($Call)
    {
        if (isset($Call['Session']['User']['Language']))
        {
            if (in_array($Call['Session']['User']['Language'], $Call['Locales']['Available']))
            {
                $Call['Locale'] = $Call['Session']['User']['Language'];
                F::Log('Locale detected by user: *'.$Call['Locale'].'*', LOG_INFO);
            }
        }
        return $Call;
    });