<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Detect', function ($Call)
    {
        list ($Call['Locale'], ) = explode('_', setlocale(LC_ALL, null));
        F::Log('System suggest locale *'.$Call['Locale'].'*', LOG_INFO + 0.5);

        return $Call;
    });