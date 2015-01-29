<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (mb_strlen($Call['Value']) > $Call['Chars'])
            $Cutted = mb_substr($Call['Value'], 0, $Call['Chars']);
        else
            $Cutted = $Call['Value'];

        return $Cutted;
    });