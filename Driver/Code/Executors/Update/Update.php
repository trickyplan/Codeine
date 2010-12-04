<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Updater Executor
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 14:07
     */

    self::Fn('Run', function ($Call)
    {
        list($Call['Call']['F']) = explode('::', $Call['Call']['F']);
        $File = Data::Locate('Driver',$Call['Call']['F'].'/'.$Call['Call']['D'].'.php');
        
        $Source = file_get_contents($File);
        preg_match('/version (.*)/',$Source, $Pockets);
        $Version = $Pockets[1];
        preg_match('/origin (.*)/',$Source, $Pockets);
        $Origin = $Pockets[1];

        $NewSource = file_get_contents($Origin);
        preg_match('/version (.*)/',$NewSource, $Pockets);
        $NewVersion = $Pockets[1];

        if ((float)$NewVersion > (float)$Version)
        {
            file_put_contents($File, $NewSource);
            Code::On('Code', 'DriverUpdated', $Call);
        }
        else
            Code::On('Code', 'NoNewDriverAvailable', $Call);
    });