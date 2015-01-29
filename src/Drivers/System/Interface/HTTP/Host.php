<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (preg_match('/:/', $_SERVER['HTTP_HOST']))
            list ($_SERVER['HTTP_HOST'], $Call['HTTP']['Port']) = explode(':', $_SERVER['HTTP_HOST']);

        if (isset($Call['Project']['Hosts'][F::Environment()]))
        {
            if (preg_match('/(\S+)\.'.$Call['Project']['Hosts'][F::Environment()].'/', $_SERVER['HTTP_HOST'], $Subdomains)
            && isset($Call['Subdomains'][$Subdomains[1]]))
            {
                $Call = F::Merge($Call, $Call['Subdomains'][$Subdomains[1]]);
                F::Log('Active Subdomain detected: '.$Subdomains[1], LOG_INFO);
            }

            $_SERVER['HTTP_HOST'] = $Call['Project']['Hosts'][F::Environment()];
        }

        $Call['HTTP']['Host'] = $_SERVER['HTTP_HOST'];

        F::Log('Host is *'.$Call['HTTP']['Host'].'*', LOG_INFO);
        $Call = F::loadOptions($Call['HTTP']['Host'], null, $Call);

        return $Call;
    });