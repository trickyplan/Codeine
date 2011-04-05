<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 28.03.11
     * @time 2:50
     */

    self::Fn('Print', function ($Call)
    {
        if (($SQL = Data::Pull('SQL.Overall')) == 0) $SQL = 'No';
        if (($FS = Data::Pull('FS.Overall')) == 0) $FS = 'No';
        if (($Network = Data::Pull('Network.Overall')) == 0) $Network = 'No';

        echo str_replace(
            array('$Time','$Memory', '$SQL','$Network', '$FS','$Calls', '$On', '$Catched'),
            array(
                round(Data::Pull('Timer.Front')*1000,2),
                Data::Pull('Memory.Front')/1024,
                $SQL,
                $Network,
                $FS,
                Code::$Counters['Call'],
                Code::$Counters['On'],
                Code::$Counters['On.Catched']),
            Data::Read('Layout::Profiler')
        );
    });
