<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeLocaleDetect', function ($Call)
    {
        foreach ($Call['Locales']['Available'] as $Locale)
            if (preg_match('@/'.$Locale.'@', $Call['HTTP']['URL']))
            {
                $Call['HTTP']['URL'] = str_replace('/'.$Locale, '', $Call['HTTP']['URL']);
                $Call['HTTP']['URI'] = str_replace('/'.$Locale, '', $Call['HTTP']['URI']);
                $Call['Run'] = str_replace('/'.$Locale, '', $Call['HTTP']['URI']);
                $Call['Locale'] = $Locale;
                F::Log('Locale detected by path: *'.$Locale.'*', LOG_INFO);
            }
       
        return $Call;
    });