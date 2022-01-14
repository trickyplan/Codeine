<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Standart MD5
     * @package Codeine
     * @version 8.x
     * @date 22.11.10
     * @time 4:40
     */

    setFn('Get', function ($Call)
    {
        $Hash = null;

        if ($HashMode = F::Dot($Call, 'Security.Hash.Mode'))
        {
            if ($HashModeCall = F::Dot($Call, 'Security.Hash.Modes.'.$HashMode))
                $Hash = F::Live($HashModeCall, $Call);
            else
                F::Log('Unknown Security.Hash.Mode *'.$HashMode.'*', LOG_ERR);
        }
        elseif ($HashMode = F::Dot($Call, 'Security.Hash.Mode'))
        {
            F::Log('*Mode* is *deprecated*, please use Security.Hash.Mode && Security.Hash.Modes', LOG_WARNING);

            if ($HashModeCall = F::Dot($Call, 'Modes.'.$HashMode))
                $Hash = F::Live($HashModeCall, $Call);
            else
                F::Log('Unknown Security.Hash.Mode *'.$HashMode.'*', LOG_ERR);
        }
        else
            F::Log('Please specify Security.Hash.Mode', LOG_ERR);

        return $Hash;
    });
