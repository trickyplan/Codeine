<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 13.08.11
     * @time 22:37
     */

    self::Fn('Open', function ($Call)
    {
        return true;
    });

    self::Fn('Load', function ($Call)
    {
        $ic = 0;

        if (is_array($Call['ID']))
        {
            foreach ($Call['ID'] as $cID)
                if ($Filename[++$ic] = F::findFile(implode('/', array($Call['URL'], $cID))))
                    break;
        }
        else
            $Filename[$ic] = F::findFile(implode('/', array($Call['URL'], $Call['ID'])));

        if($Filename[$ic])
            return file_get_contents($Filename[$ic]);

    });


    self::Fn('Create', function ($Call)
    {
        $ic = 0;

        if (is_array($Call['ID']))
        {
            foreach ($Call['ID'] as $cID)
                if ($Filename[++$ic] = F::findFile(implode('/', array($Call['URL'], $cID))))
                    break;
        }
        else
            $Filename[$ic] = F::findFile(implode('/', array($Call['URL'], $Call['ID'])));

        if($Filename[$ic])
            return file_put_contents($Filename[$ic], $Call['Value']);
    });