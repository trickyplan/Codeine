<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if ($Call['View']['Renderer']['Service'] == 'View.HTML')
            return F::Apply(null, gettype($Call['Data']), $Call);
        else
            return $Call;
    });

    setFn('boolean', function ($Call)
    {
        print $Call['Data']? 'true': 'false';
        return $Call['Data'];
    });

    setFn('integer', function ($Call)
    {
        print $Call['Data'];
        return $Call['Data'];
    });

    setFn('double', function ($Call)
    {
        print $Call['Data'];
        return $Call['Data'];
    });

    setFn('string', function ($Call)
    {
        print $Call['Data'];
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
        print 'null';
        return $Call['Data'];
    });

    setFn('unknown type', function ($Call)
    {
        print 'Unknown type';
        return $Call['Data'];
    });