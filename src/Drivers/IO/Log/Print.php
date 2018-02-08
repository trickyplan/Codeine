<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        return true;
    });
    
    setFn('Write', function ($Call)
    {
        $Allowed = (in_array($Call['View']['Renderer']['Service'], $Call['IO']['Log']['Print']['Allowed Renderers'])
            &&
            in_array($Call['Context'], $Call['IO']['Log']['Print']['Allowed Context']));
        
        if (F::Dot($Call, 'IO.Log.Print.Force') || $Allowed)
            return F::Apply(null, gettype($Call['Data']), $Call);
        else
            return null;
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