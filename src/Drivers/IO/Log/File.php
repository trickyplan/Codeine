<?php

    /* Codeine
     * @author BreathLess
     * @description Console Object Support
     * @package Codeine
     * @version 7.x
     */

    setFn('Open', function ($Call)
    {
        $File = fopen($Call['Directory'].DS.$Call['Scope'].'.log', 'a+');
        return $File;
    });

    setFn('Write', function ($Call)
    {
        foreach ($Call['Data'] as $Row)
            fwrite($Call['Link'], $Row[1]."\t".$Row[3].PHP_EOL."\t".$Row[2].PHP_EOL);

        return true;
    });

    setFn('Close', function ($Call)
    {
        return fclose($Call['Link']);
    });