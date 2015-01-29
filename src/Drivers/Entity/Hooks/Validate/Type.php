<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Node']['Type']) && $Call['Node']['Type'])
        {
            if (!isset($Call['Data'][$Call['Name']]) or empty($Call['Data'][$Call['Name']]))
                return 'Required';
        }
        return true;
    });