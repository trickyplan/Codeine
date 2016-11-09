<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Console Object Support
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call)
    {
        return true;
    });

    setFn('Write', function ($Call)
    {
        foreach ($Call['Data'] as $Row)
        {
            $Error = F::Run('Entity', 'Create',
                [
                    'Entity' => 'Error',
                    'Data'   =>
                    [
                        'Channel'   => $Call['Channel'],
                        'Location'  => $Row[3],
                        'Title'     => $Row[2],
                        'Severity'  => $Call['Levels'][$Row[0]],
                        'URL'       => $Call['HTTP']['URL'],
                        'Time'      => $Row[1],
                        'Stack'     => $Row[5]
                    ]
                ]);
        }

        return true;
    });

    setFn('Close', function ($Call)
    {
        return closelog();
    });