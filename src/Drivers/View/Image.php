<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Process', function ($Call)
    {
        $Call['HTTP']['Headers']['Content-type:'] = 'image/png';
        $Call['Output'] = $Call['Image'];

        return $Call;
    });