<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Touch', function ($Call)
    {
        return touch($Call['Filename']);
    });

    setFn ('Create', function ($Call)
        {
            return file_put_contents($Call['Filename'], F::Live($Call['Value']));
        });

    setFn ('Copy', function ($Call)
        {
            return copy ($Call['From'], $Call['To']);
        });