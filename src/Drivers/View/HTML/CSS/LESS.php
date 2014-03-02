<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
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
            // FIXME! Temporary decision.
            shell_exec('lessc '.Root.'/Assets/'.$Asset.'/less/'.$ID.'.less > '.Root.'/Assets/'.$Asset.'/css/'.$ID.'.css');
        }

        return $Call;
    });