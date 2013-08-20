<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        $Call = F::Run(null, gettype($Call['Data']), $Call);
        return $Call;
    });

    setFn('boolean', function ($Call)
    {
        echo $Call['Data']? 'true': 'false';
        return $Call;
    });

    setFn('integer', function ($Call)
    {
        echo $Call['Data'];
        return $Call;
    });

    setFn('double', function ($Call)
    {
        echo $Call['Data'];
        return $Call;
    });

    setFn('string', function ($Call)
    {
        echo $Call['Data'];
        return $Call;
    });

    setFn('array', function ($Call)
    {
        print_r($Call['Data']);
        return $Call;
    });

    setFn('object', function ($Call)
    {
        print_r($Call['Data']);
        return $Call;
    });

    setFn('resource', function ($Call)
    {
        print_r($Call['Data']);
        return $Call;
    });

    setFn('NULL', function ($Call)
    {
        echo 'null';
        return $Call;
    });

    setFn('unknown type', function ($Call)
    {
        echo 'Unknown type';
        return $Call;
    });