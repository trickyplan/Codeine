<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.6.2
     * @date 13.08.11
     * @time 22:37
     */

    self::setFn ('Write', function ($Call)
    {
        if(!empty($Call['Value']['name']))
        {
            $Name = F::Live($Call['Naming'], $Call);

            if (isset($Call['MIME']))
                if (!in_array($Call['Value']['type'],$Call['MIME']))
                    return null;

            move_uploaded_file($Call['Value']['tmp_name'], Root . '/' . $Call['Directory'] . '/' . $Call['Scope'] . '/' . $Name);

            return $Name;
        }
        else
            return null;

    });