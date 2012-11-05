<?php

    /* Sphinx
     * @author BreathLess
     * @description  
     * @package Sphinx
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Status'][] =
            array(
                'Type' => 'Text',
            'Value' => file_get_contents('http://'.$Call['Host'].'/nginx_status')
        );

        return $Call;
    });

    setFn('Errors', function ($Call)
    {
        $Call['Output']['Content'][] =
        array(
            'Type' => 'Text',
            'Value' => '<pre>'.shell_exec('tail -n25 /var/log/nginx/error.log').'</pre>'
        );

        return $Call;
    });

    setFn('Access', function ($Call)
    {
        $Call['Output']['Content'][] =
        array(
            'Type' => 'Text',
            'Value' => '<pre>'.shell_exec('tail -n25 /var/log/nginx/error.log').'</pre>'
        );

        return $Call;
    });