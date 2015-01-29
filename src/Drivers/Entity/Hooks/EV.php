<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeOperation', function ($Call)
    {
        $Call['Data']['EV'] = $Call['EV'];

        return $Call;
    });