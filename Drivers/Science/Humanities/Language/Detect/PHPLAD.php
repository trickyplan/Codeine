<?php

    /* Codeine
     * @author BreathLess
     * @description: Wrapper for http://code.google.com/p/phplangautodetect/
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 3:26
     */

    self::Fn('Detect', function ($Call)
    {
        include $Call['Options']['Paths']['Class'];
        
        $Detector = new Lang_Auto_Detect();

        return $Detector->lang_detect($Call['Value']);
    });
