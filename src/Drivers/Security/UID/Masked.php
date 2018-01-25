<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Random integer
     * @package Codeine
     * @version 8.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        $Output = '';

        $Call['Mask'] = str_split($Call['Mask'], 1);
        $SZ = count($Call['Mask']);
        
        for ($IX = 0; $IX < $SZ; $IX++)
            if ($Call['Mask'][$IX] == '\\')
            {
                $Subset = F::Dot($Call, 'UID.Masked.Subset.'.$Call['Mask'][$IX+1]);
                $Output.= substr($Subset, rand(0, strlen($Subset)), 1);
                $IX++;
            }
            else
                $Output.= $Call['Mask'][$IX];

        return $Output;
    });
