<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Make', function ($Call)
    {
        if ($Call['Value'] == 0)
            $Call['Value'] = time();

        if (is_numeric($Call['Value']))
            $Call['Value'] = date($Call['Datepicker']['Format'], F::Live($Call['Value']));

        return $Call;
     });