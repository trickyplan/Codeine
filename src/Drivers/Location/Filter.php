<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Session']['Location']) && $Call['Session']['Location'] != 0 && is_array($Call['Where']))
            $Call['Where']['Location'] = $Call['Session']['Location'];

        return $Call;
    });