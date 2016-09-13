<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('Get', function ($Call)
    {
        return
            $Call['HTTP']['Proto']
            .$Call['Gravatar']['URL']
            .md5(strtolower(trim($Call['Data']['EMail'])))
            .'?s='
            .$Call['Gravatar']['Size']
            .urlencode('&d='.$Call['HTTP']['Host'].'/img/no.jpg');
    });