<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Wrapper
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 11.03.11
     * @time 1:03
     */

    self::Fn('Percent', function ($Call)
    {
        exec('ping -nq -l 3 -c '.$Call['Count'].' '.$Call['Host'], $exec);
        preg_match('/(\d+)\%/',$exec[3], $pockets);
        return 100-$pockets[1];
    });

    self::Fn('Times', function ($Call)
    {
        exec('ping -nq -l 3 -c '.$Call['Count'].' '.$Call['Host'], $exec);
        preg_match_all('/([\d\.]+)/',$exec[4], $pockets);
        return array(
            'Minimum'=>$pockets[0][0],
            'Average'=>$pockets[0][1],
            'Maximum'=>$pockets[0][2],
            'Deviation'=>$pockets[0][3]
        );
    });
