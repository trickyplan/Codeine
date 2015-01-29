<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        foreach ($Call['Filesize']['Units'] as $Unit => $Threshold)
        {
            if ($Call['Value'] > $Threshold*0.9)
            {
                $Call['Value'] = round($Call['Value']/$Threshold, 2).$Unit;
                break;
            }
        }

        return $Call['Value'];
    });