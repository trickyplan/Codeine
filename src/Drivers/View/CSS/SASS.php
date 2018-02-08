<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        list($Asset, $ID) = F::Run('View', 'Asset.Route',
            [
                'Value' => $Call['CSS Name']
            ]);

        $SASS = F::Run('IO', 'Execute',
            [
                'Execute' => 'Exist',
                'Storage' => 'SASS',
                'Scope'   => [$Asset, 'sass'],
                'Where'   => $ID
            ]);

        if ($SASS)
        {
            $SASSVersion = F::Run('IO', 'Execute',
            [
                'Execute' => 'Version',
                'Storage' => 'SASS',
                'Scope'   => [$Asset, 'sass'],
                'Where'   => $ID
            ]);

            $CSSVersion = F::Run('IO', 'Execute',
                [
                    'Execute' => 'Version',
                    'Storage' => 'CSS',
                    'Scope'   => [$Asset, 'css'],
                    'Where'   => $ID
                ]);

            if (($SASSVersion > $CSSVersion) or (isset($Call['HTTP']['Request']['Headers']['Pragma']) && $Call['HTTP']['Request']['Headers']['Pragma'] == 'no-cache'))
            {
                // FIXME! Temporary decision.
                if (file_exists(Root.'/Assets/'.$Asset.'/sass/'.$ID.'.scss'))
                {
                    $Command = 'sass '.Root.'/Assets/'.$Asset.'/sass/'.$ID.'.scss > '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css';
                    F::Log('SASS *processed* '.Root.'/Assets/'.$Asset.'/sass/'.$ID.'.scss', LOG_DEBUG, 'Developer');
                    F::Log($Command, LOG_INFO, 'Developer');
                    exec($Command, $ExecOutput, $ExecReturn);
                    F::Log('Output: '.implode(PHP_EOL, $ExecOutput), LOG_INFO, 'Developer');
                    F::Log('Error Code:'.$ExecReturn, LOG_INFO, 'Developer');
                }
            }
            else
                F::Log('SASS *skipped* '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css', LOG_DEBUG, 'Developer');
        }

        return $Call;
    });