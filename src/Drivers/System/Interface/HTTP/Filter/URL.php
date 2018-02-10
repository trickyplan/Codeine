<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        foreach ($Call['HTTP']['Filter']['URL']['Rules'] as $FilterName => $URLSet)
            foreach ($URLSet as $URL)
                if ($Call['HTTP']['URL'] == $URL)
                {
                    F::Log('HTTP *URL* Filter *'.$FilterName.'* matched', LOG_NOTICE, 'Security');
                    return false;
                }
                
        return true;
    });