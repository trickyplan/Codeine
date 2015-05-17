<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Call['CSS Filename']]);

        $LESS = F::Run('IO', 'Execute',
            [
                'Execute' => 'Exist',
                'Storage' => 'LESS',
                'Scope'   => [$Asset, 'less'],
                'Where'   => $ID
            ]);

        if ($LESS)
        {
            $LESSVersion = F::Run('IO', 'Execute',
            [
                'Execute' => 'Version',
                'Storage' => 'LESS',
                'Scope'   => [$Asset, 'less'],
                'Where'   => $ID
            ]);

            $CSSVersion = F::Run('IO', 'Execute',
                [
                    'Execute' => 'Version',
                    'Storage' => 'CSS',
                    'Scope'   => [$Asset, 'css'],
                    'Where'   => $ID
                ]);

            if ($LESSVersion != $CSSVersion or (isset($Call['HTTP']['Request']['Headers']['Pragma']) && $Call['HTTP']['Request']['Headers']['Pragma'] == 'no-cache'))
            {
                // FIXME! Temporary decision.
                if (file_exists(Root.'/Assets/'.$Asset.'/less/'.$ID.'.less'))
                {
                    $Command = 'lessc '.Root.'/Assets/'.$Asset.'/less/'.$ID.'.less > '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css';
                    shell_exec($Command);
                    $Command = 'lessc --clean-css '.Root.'/Assets/'.$Asset.'/less/'.$ID.'.less > '.Root.'/Assets/'.$Asset.'/css/min.css';
                    shell_exec($Command);
                    F::Log('LESS *processed* '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css', LOG_INFO, 'Developer');

                }
            }
            else
                F::Log('LESS *skipped* '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css', LOG_DEBUG, 'Developer');
        }

        return $Call;
    });