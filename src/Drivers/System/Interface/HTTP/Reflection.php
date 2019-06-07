<?php

/* Codeine
 * @author bergstein@trickyplan.com
 * @description  
 * @package Codeine
 * @version 2019.x
 */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'] = $Call['HTTP']['Request']['Headers'];
        return $Call;
    });