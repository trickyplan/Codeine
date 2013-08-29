<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Age', function ($Call)
    {
        if (isset($Call['Data']['DOB']) && $Call['Data']['DOB'] != 0)
            return floor((time()-$Call['Data']['DOB'])/(86400*365));
        else
            return 0;
    });

    setFn('Location', function ($Call)
    {
        return F::Run('System.GeoIP', 'City', $Call);
    });

    setFn('Photo', function ($Call)
    {
        if (isset($Call['Data']['Photo']) && !empty($Call['Data']['Photo']))
            $Photo = '/Public/uploads/user/'.$Call['Data']['Photo']; // FIXME I'm shitcode.
        else
            $Photo = F::Run('Services.Avatar.Gravatar', 'Get', ['EMail' => $Call['Data']['EMail']]);

        return $Photo;
    });