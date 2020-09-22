<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        F::Log('Entity.List is deprecated (renamed). Switch to Entity.List.Static', LOG_WARNING);  
        return F::Apply('Entity.List.Static', null, $Call);
    });

    setFn('RAW', function ($Call)
    {
        F::Log('Entity.List is deprecated (renamed). Switch to Entity.List.Static', LOG_WARNING);
        return F::Apply('Entity.List.Static', null, $Call);
    });

    setFn('RAW2', function ($Call)
    {
        F::Log('Entity.List is deprecated (renamed). Switch to Entity.List.Static', LOG_WARNING);
        return F::Apply('Entity.List.Static', null, $Call);
    });