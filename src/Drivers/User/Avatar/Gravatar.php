<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('Get', function ($Call)
    {
        $EMail = trim($Call['Data']['EMail']);
        $EMail = mb_convert_case($EMail, MB_CASE_LOWER);
        $Default = urlencode($Call['Gravatar']['Default']);

        $URL = '//'
                .$Call['Gravatar']['URL']
                .md5($EMail)
                .'?'
                .http_build_query(
                    [
                        's' => $Call['Gravatar']['Size'],
                        'd' => $Default
                    ]);

        return $URL;
    });