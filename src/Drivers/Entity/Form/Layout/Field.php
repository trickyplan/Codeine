<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Add', function ($Call)
    {
        $Call['Output'][$Call['Name']][] = $Call['Widget'];
        return $Call;
    });