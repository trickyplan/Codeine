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

        if (!empty($Call['Data']))
        foreach ($Call['Data'] as $IX => $Row)
            $Output[] = implode("\t", ['['.RequestID.']', 'V'.$Row['V'], $Row['T'], $Row['H'], $Row['R'], $Row['X']]);

        $Output = implode(PHP_EOL, $Output).PHP_EOL;
        
        // $Output = preg_replace('/\*(.*)\*/SsUu', '$1', $Output);

        return $Output;
    });