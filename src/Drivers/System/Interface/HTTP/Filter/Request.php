<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Request']))
            foreach ($Call['HTTP']['Filter']['Request']['Rules'] as $FilterName => $Filter)
                foreach ($Filter['Match'] as $Match)
                    if (F::Diff($Match, $Call['Request']) === null)
                    {
                        if ($Filter['Decision'])
                            ;
                        else
                        {
                            F::Log('HTTP Request Filter *'.$FilterName.'* matched', LOG_WARNING, 'Security');
                            return false;
                        }
                    }
                        
        return true;
    });