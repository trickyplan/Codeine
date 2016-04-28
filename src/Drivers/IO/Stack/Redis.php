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
        F::Log('Pull: '.$Call['Scope'].$Call['Queue'], LOG_INFO, 'Administrator');
        if (($Result = $Call['Link']->lPop($Call['Scope'].$Call['Queue'])) !== false)
            return [jd($Result, true)];
        else
            return null;
    });

    setFn('Write', function ($Call)
    {
        F::Log('Push: '.$Call['Scope'].$Call['Queue'], LOG_INFO, 'Administrator');
        return $Call['Link']->lPush($Call['Scope'].$Call['Queue'], j($Call['Data']));
    });

    setFn('Count', function ($Call)
    {
        F::Log('Count: '.$Call['Scope'].$Call['Queue'], LOG_INFO, 'Administrator');
        return $Call['Link']->lLen($Call['Scope'].$Call['Queue']);
    });