<?php

    /* Sphinx
     * @author bergstein@trickyplan.com
     * @description  
     * @package Sphinx
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
/*        $Call['Output']['Content'][] =
            [
                'Type'  => 'Block',
                'Class' => 'alert alert-info',
                'Value' => shell_exec('nginx -v')
            ];*/

        return $Call;
    });

    setFn('Status', function ($Call)
    {
        $Fields =
            [
                'Connections.Active',
                'Connections.Accepted',
                'Connections.Handled',
                'Requests.Handled',
                'Connections.Reading',
                'Connections.Writing',
                'Connections.Waiting'
            ];

        $Status = file_get_contents('http://'.$Call['HTTP']['Host'].'/nginx_status');

        if (preg_match_all('/(\d+)/', $Status, $Pockets))
        {
            $Data = [];

            foreach ($Pockets[1] as $IX => $Value)
                $Data[] = ['<l>Nginx.Status:'.$Fields[$IX].'</l>', $Value];

            $Call['Output']['Content'][] =
                [
                    'Type' => 'Table',
                    'Value' => $Data
                ];
        }

        return $Call;
    });

    setFn('Log.Error', function ($Call)
    {
        $Call['Output']['Content'][] =
            [
                'Type'  => 'Block',
                'Class' => 'console-inverse',
                'Value' => shell_exec('tail -n50 /var/log/nginx/error.log')
            ];

        return $Call;
    });

    setFn('Log.Access', function ($Call)
    {
        $Call['Output']['Content'][] =
            [
                'Type'  => 'Block',
                'Class' => 'console-inverse',
                'Value' => shell_exec('tail -n50 /var/log/nginx/'.$Call['HTTP']['Host'].'.access.log')
            ];

        return $Call;
    });