<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Read', function ($Call)
    {
        if (preg_match('/<\?xml/', $Call['Value']))
            return jd(j(simplexml_load_string($Call['Value'], null, LIBXML_NOCDATA), JSON_NUMERIC_CHECK), true);
        else
            return jd(j(simplexml_load_string('<root>'.$Call['Value'].'</root>'), JSON_NUMERIC_CHECK), true);
    });