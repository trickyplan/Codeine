<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (is_array($Call['Project']['Hosts'][F::Environment()]))
            $Hosts = $Call['Project']['Hosts'][F::Environment()];
        else
            $Hosts = [$Call['Project']['Hosts'][F::Environment()]];

        if (isset($_SERVER['HTTP_HOST']))
            $Host = $_SERVER['HTTP_HOST'];
        else
        {
            $Host = $Hosts[0];
            F::Log('Host not specified, default selected', LOG_WARNING);
        }

        if (preg_match('/:/', $_SERVER['HTTP_HOST']))
            list ($_SERVER['HTTP_HOST'], $Call['HTTP']['Port']) = explode(':', $_SERVER['HTTP_HOST']);

        if (in_array($Host, $Hosts))
        {
            if (isset($Call['Project']['Active Hosts'][$Host]))
            {
                $Call = F::Merge($Call, $Call['Project']['Active Hosts'][$Host]);
                F::Log('Active Host selected: *'.$Host.'*', LOG_INFO);
            }
        }
        else
        {
            if (is_array($Call['Project']['Hosts'][F::Environment()]))
                $_SERVER['HTTP_HOST'] = $Call['Project']['Hosts'][F::Environment()][0];
            else
                $_SERVER['HTTP_HOST'] = $Call['Project']['Hosts'][F::Environment()];
            
            F::Log('Default domain selected *'.$_SERVER['HTTP_HOST'].'*');
        }

        $Call['HTTP']['Host'] = $_SERVER['HTTP_HOST'];

        if (isset($Call['HTTP']['Domain']))
            ;
        else
            $Call['HTTP']['Domain'] = $Call['HTTP']['Host'];

        F::Log('Host is *'.$Call['HTTP']['Host'].'*', LOG_INFO);
        $Call = F::loadOptions($Call['HTTP']['Host'], null, $Call);

        return $Call;
    });