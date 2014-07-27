<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('beforeOperation', function ($Call)
    {
        if (isset($Call['Location']))
            $Call['Where']['Location'] = $Call['Location']['ID'];

        return $Call;
    });