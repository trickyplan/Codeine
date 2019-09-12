<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeEntityWrite', function ($Call)
    {
        if (isset($Call['Model']))
            $Call['Data']['EH'] = mb_substr(sha1(serialize($Call['Model'])), -8 , 8);
        else
            $Call['Data']['EH'] = '';

        return $Call;
    });