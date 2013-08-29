<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('Get', function ($Call)
    {
        $Call = F::Run('System.Interface.Web', 'Protocol', $Call);
        return
            $Call['Proto']
            .$Call['Gravatar']['URL']
            .md5(strtolower(trim($Call['EMail'])))
            .'?s='
            .$Call['Gravatar']['Size']
            .urlencode('&d='.$Call['Host'].'/img/no.jpg');
    });