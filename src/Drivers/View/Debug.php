<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Dumper
     * @package Codeine
     * @version 8.x
     */

    setFn('Detect', function ($Call)
    {
        return true;
    });

    setFn('Render', function ($Call)
    {
        $Call['HTTP']['Headers']['Content-Type:'] = 'text/plain';
        return var_export($Call['Value'], true);
    });
