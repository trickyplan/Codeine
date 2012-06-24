<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.4.5
     * @date 13.08.11
     * @time 22:37
     */

    self::setFn ('Write', function ($Call)
    {

        if(!empty($Call['Value']['name']))
        {
            $Name = F::Live($Call['Naming'], $Call);

            move_uploaded_file($Call['Value']['tmp_name'], Root . '/' . $Call['Directory'] . '/' . $Call['Scope'] . '/' . $Name);

            return $Call['Directory'].'/'.$Name;
        }
        else
            return null;

    });