<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if ($Call['View']['Renderer']['Service'] == 'View.HTML')
            return F::Apply(null, gettype($Call['Data']), $Call);
        else
            return null;
    });

    setFn('boolean', function ($Call)
    {
        echo $Call['Data']? 'true': 'false';
        return $Call['Data'];
    });

    setFn('integer', function ($Call)
    {
        echo $Call['Data'];
        return $Call['Data'];
    });

    setFn('double', function ($Call)
    {
        echo $Call['Data'];
        return $Call['Data'];
    });

    setFn('string', function ($Call)
    {
        echo $Call['Data'];
        return $Call['Data'];
    });

    setFn('array', function ($Call)
    {
        print_r($Call['Data']);
        return $Call['Data'];
    });

    setFn('object', function ($Call)
    {
        print_r($Call['Data']);
        return $Call['Data'];
    });

    setFn('resource', function ($Call)
    {
        print_r($Call['Data']);
        return $Call['Data'];
    });

    setFn('NULL', function ($Call)
    {
        echo 'null';
        return $Call['Data'];
    });

    setFn('unknown type', function ($Call)
    {
        echo 'Unknown type';
        return $Call['Data'];
    });