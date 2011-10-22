<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 7:04
     */

    self::Fn('Do', function ($Call)
    {
        // FIXME Data read

        $Source = file_get_contents(Codeine.'/Options/Stub.json');

        // FIXME Data write
        $Directory = explode('.', $Call['Value']['_N']);
        array_pop($Directory);
        $Directory = Codeine.'/Options/'.implode('/', $Directory);

        if (!is_dir($Directory))
            mkdir($Directory, 0755, true);

        file_put_contents(Codeine.'/Options/'.strtr($Call['Value']['_N'],'.','/').'.json', $Source);

        return $Source;
    });
