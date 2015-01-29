<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        $Call['Value'] = preg_replace('/ - /', ' — ', $Call['Value']);

        return $Call;
     });