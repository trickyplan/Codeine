<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Detect', function ($Call)
    {
        $Call = F::Hook('beforeLocaleDetect', $Call);
               
            if (isset($Call['Locale']))
                F::Log('Locale selected: *'.$Call['Locale'].'*', LOG_INFO);
            else
            {
                $Call['Locale'] = $Call['Default']['Locale'];
                
                if (F::Dot($Call, 'Locales.Detect.Accept-language'))
                    $Call = F::Apply(null, 'Check Accept-language', $Call);
            }
    
            setlocale(LC_ALL, $Call['Locales']['PHP'][$Call['Locale']]);
    
            if ($Call['Locale'] == $Call['Default']['Locale'])
                $Call['LocaleURL'] = '';
            else
                $Call['LocaleURL'] = '/'.$Call['Locale']; // FIXME Not elegant
        
        $Call = F::Hook('afterLocaleDetect', $Call);
                
        return $Call;
    });
    
    setFn('Check Accept-language', function ($Call)
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            preg_match_all (
                '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
                $_SERVER['HTTP_ACCEPT_LANGUAGE'], $Parsed);

            $Locales = array_combine ($Parsed[1], $Parsed[4]);

            foreach ($Locales as $Locale => $Q)
                if ($Q === '') $Locales[$Locale] = 1;

            arsort ($Locales, SORT_NUMERIC);

            foreach ($Locales as $Locale => $Quality)
            {
                if (isset($Call['Accept-Language'][$Locale]))
                {
                    $Call['Locale'] = $Call['Accept-Language'][$Locale];
                    break;
                }
            }

            if (isset($Call['Locale']))
                F::Log('Accept-Language suggest locale *'.$Call['Locale'].'*', LOG_INFO + 0.5);
        }
        
        return $Call;
    });