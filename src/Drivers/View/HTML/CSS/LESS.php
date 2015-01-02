<?php

    /* Codeine
     * @author BreathLess
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

            if ($LESSVersion != $CSSVersion)
            {
                // FIXME! Temporary decision.
                shell_exec('lessc --clean-css '.Root.'/Assets/'.$Asset.'/less/'.$ID.'.less > '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css');
                F::Log('LESS processed '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css', LOG_GOOD, 'Developer');
            }
        }

        return $Call;
    });