<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Add', function ($Call)
    {
       $Call['Output']['Form'][] = $Call['Widget'];
       return $Call;
    });