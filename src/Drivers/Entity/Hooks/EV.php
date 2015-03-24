<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeOperation', function ($Call)
    {
        if (isset($Call['EV']))
            $Call['Data']['EV'] = $Call['EV'];
        else
            $Call['Data']['EV'] = 0;

        return $Call;
    });