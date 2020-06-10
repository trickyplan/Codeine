<?php

/* Codeine
 * @author bergstein@trickyplan.com
 * @description
 * @package Codeine
 * @version 2020.x.x
 */

    setFn('Do', function ($Call)
    {
        return ($Call['Value'] * F::Dot($Call,'Math.Conversion.Information.'.$Call['From'])) / F::Dot($Call,'Math.Conversion.Information.'.$Call['To']);
    });
