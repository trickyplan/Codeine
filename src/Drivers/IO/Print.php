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
        return F::Apply(null, gettype($Call['Data']), $Call);
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
        if (preg_match_all('/<timer>(.*)<\/timer>/SsUu', $Call['Data'], $Timers))
            foreach ($Timers[1] as $IX => $Key)
                $Call['Data'] = str_replace($Timers[0][$IX], F::Time($Key), $Call['Data']);

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
        return '';
    });

    setFn('unknown type', function ($Call)
    {
        print 'Unknown type';
        return $Call['Data'];
    });