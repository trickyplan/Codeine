<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Make', function ($Call)
    {
        $Call['Value'] = $Call['Scope'].':'.$Call['Data']['ID'];

        return $Call;
    });