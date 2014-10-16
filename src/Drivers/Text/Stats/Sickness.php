<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Words', function ($Call)
    {
        if (isset($Call['Data'][$Call['Key']]))
        {
            $Text= strip_tags($Call['Data'][$Call['Key']]);
            $Words = preg_split('~[\s0-9_]|[^\w]~u', $Text, -1, PREG_SPLIT_NO_EMPTY);

            return array_count_values($Words);
        }
        else
            return [];
    });

    setFn('Calculate', function ($Call)
    {
        $Words = F::Run(null, 'Words', $Call);

        $Overall = count($Words);
        if ($Overall>0)

        {$Repeated = 0;

        foreach ($Words as $Word => $Count)
            if ($Count > 1)
                $Repeated+=$Count-1;

        return round($Repeated/$Overall*100);}
        else
            return 0;
    });