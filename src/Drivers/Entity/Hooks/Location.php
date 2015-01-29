<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('beforeOperation', function ($Call)
    {
        if (isset($Call['Location']['ID']))
            $Call['Where']['Location'] = $Call['Location']['ID'];

        return $Call;
    });