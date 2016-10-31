<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        // Check port present? Why i'm doing this?
        /* if (preg_match('/:/', $_SERVER['HTTP_HOST']))
            list ($_SERVER['HTTP_HOST'], $Call['HTTP']['Port']) = explode(':', $_SERVER['HTTP_HOST']);*/
        
        F::Log('Host Strategy *'.$Call['HTTP']['Host Strategy'].'* selected', LOG_INFO);
        $Call = F::Apply($Call['HTTP']['Host Strategy'], 'Do', $Call);

        if (isset($Call['HTTP']['Domain']))
            ;
        else
            $Call['HTTP']['Domain'] = $Call['HTTP']['Host'];

        F::Log('Host is *'.$Call['HTTP']['Host'].'*', LOG_INFO);
        $Call = F::loadOptions($Call['HTTP']['Host'], null, $Call);

        return $Call;
    });