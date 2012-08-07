<?php

    /* Sphinx
     * @author BreathLess
     * @description  
     * @package Sphinx
     * @version 7.6.2
     */

    self::setFn('Do', function ($Call)
    {
        $Call['Output']['Status'][] =
            array(
                'Type' => 'Text',
            'Value' => file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/nginx_status')
        );

        return $Call;
    });

    self::setFn('Errors', function ($Call)
    {
        $Call['Output']['Content'][] =
        array(
            'Type' => 'Text',
            'Value' => '<pre>'.shell_exec('tail -n25 /var/log/nginx/error.log').'</pre>'
        );

        return $Call;
    });

    self::setFn('Access', function ($Call)
    {
        $Call['Output']['Content'][] =
        array(
            'Type' => 'Text',
            'Value' => '<pre>'.shell_exec('tail -n25 /var/log/nginx/error.log').'</pre>'
        );

        return $Call;
    });