<?php

    /* Sphinx
     * @author BreathLess
     * @description  
     * @package Sphinx
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Status'][] =
            [
                'Type' => 'Text',
                'Value' => file_get_contents('http://'.$Call['HTTP']['Host'].'/nginx_status')
            ];

        return $Call;
    });

    setFn('Errors', function ($Call)
    {
        $Call['Output']['Content'][] =
            [
                'Type' => 'Text',
                'Value' => '<pre>'.shell_exec('tail -n25 /var/log/nginx/'.$Call['HTTP']['Host'].'.error.log').'</pre>'
            ];

        return $Call;
    });

    setFn('Access', function ($Call)
    {
        $Call['Output']['Content'][] =
            [
                'Type' => 'Text',
                'Value' => '<pre>'.shell_exec('tail -n25 /var/log/nginx/'.$Call['HTTP']['Host'].'.access.log').'</pre>'
            ];

        return $Call;
    });