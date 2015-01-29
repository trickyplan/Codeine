<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $CanIUse = jd(F::Live($Call['Caniuse']['Source'], $Call)[0], true) ;

        foreach ($CanIUse['data'] as $Feature => $Data)
            if (isset($Data['stats'][$Call['Browser']][$Call['Version']]))
                $Output[$Feature] = $Data['stats'][$Call['Browser']][$Call['Version']];

        return $Output;
    });