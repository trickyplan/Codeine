<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        return strip_tags($Call['Value'], '<br/><b><i><u><br>'); // FIXME
    });

    setFn('Read', function ($Call)
    {
        return nl2br($Call['Value']);
    });