<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $_SERVER['REQUEST_URI'] = preg_replace('/^(\/+)/Ssu', '/', $_SERVER['REQUEST_URI']);


        $Call['HTTP']['URI'] = $_SERVER['REQUEST_URI'].(empty($Call['HTTP']['URL Query'])? '' : '');
        $Call['HTTP']['URL'] = parse_url($Call['HTTP']['URI'], PHP_URL_PATH);
        $Call['HTTP']['URL Query'] = parse_url($Call['HTTP']['URI'], PHP_URL_QUERY);

        F::Log('URL: *'.$Call['HTTP']['URL'].'*', LOG_INFO);
        F::Log('URI: *'.$Call['HTTP']['URI'].'*', LOG_INFO);

        empty ($Call['HTTP']['URL Query']) ?
            F::Log('Query string empty.', LOG_INFO):
            F::Log('Query string is: *'.$Call['HTTP']['URL Query'].'*', LOG_INFO);

        $Call['Run'] = $Call['HTTP']['URI'];
        F::Log('Run String: '.$Call['Run'], LOG_INFO);

        return $Call;
    });