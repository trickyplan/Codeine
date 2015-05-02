<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        return F::Run('IO.Storage.Redis', 'Open', $Call);
    });

    setFn('Read', function ($Call)
    {
        if (($Result = $Call['Link']->lPop($Call['Scope'])) !== false)
            return [jd($Result, true)];
        else
            return null;
    });

    setFn('Write', function ($Call)
    {
        return $Call['Link']->rPush($Call['Scope'], j($Call['Data']));
    });

    setFn('Count', function ($Call)
    {
        return $Call['Link']->lLen($Call['Scope']);
    });