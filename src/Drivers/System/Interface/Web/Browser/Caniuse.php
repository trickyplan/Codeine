<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Do', function ($Call)
    {
        $CanIUse = json_decode(F::Live($Call['Caniuse']['Source'], $Call)[0], true) ;

        foreach ($CanIUse['data'] as $Feature => $Data)
            if (isset($Data['stats'][$Call['Browser']][$Call['Version']]))
                $Output[$Feature] = $Data['stats'][$Call['Browser']][$Call['Version']];

        return $Output;
    });