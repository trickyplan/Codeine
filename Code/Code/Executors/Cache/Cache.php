<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Cache Executor
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 1:37
     */

    self::Fn('Run', function ($Call)
    {
        $CID = sha1(serialize($Call['Call']));

        if (($Cached = Data::Read(
                array('Point' => 'CodeCache',
                      'Where'=>
                            array('ID' => $CID)))) !== null)
            return $Cached;
        else
        {
            $Result = Code::Run($Call['Call'], Code::Internal);
            Data::Create(array(
                              'Point' => 'CodeCache',
                              'ID' => $CID,
                              'Data' => $Result
                         ));
            return $Result;
        }
    });