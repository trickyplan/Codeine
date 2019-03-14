<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Output = [];

        foreach ($Call['Data'] as $IX => $Row)
            $Output[] = implode("\t", ['['.REQID.']', 'V'.$Row['V'], $Row['R'], $Row['X'], $Row['X']]);

        $Output = implode(PHP_EOL, $Output).PHP_EOL;
        
        // $Output = preg_replace('/\*(.*)\*/SsUu', '$1', $Output);

        return $Output;
    });