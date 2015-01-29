<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        foreach ($Call['Map'][$Call['From']][$Call['To']] as $Pattern => $Replace)
            $Call['Value'] = preg_replace('/'.$Pattern.'/', $Replace, $Call['Value']);

        return $Call['Value'];
    });