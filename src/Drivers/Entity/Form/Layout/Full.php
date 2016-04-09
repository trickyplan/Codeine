<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Start', function ($Call)
    {
        
        return $Call;
    });

    setFn('Add', function ($Call)
    {
       $Call['Output']['Form'][] = $Call['Widget'];
       return $Call;
    });

    setFn('Finish', function ($Call)
    {
        return $Call;
    });