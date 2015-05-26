<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Value'] = strip_tags($Call['Value'], $Call['Allowed Tags']);

        // Empty Check
        if ('' === trim(preg_replace('/\<[\S]+?\>/Ssu', '', $Call['Value'])))
            return null;
        else
            return htmlspecialchars_decode($Call['Value'], ENT_HTML5 | ENT_QUOTES);
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value'];
    });