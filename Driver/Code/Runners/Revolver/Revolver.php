<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Revolver Runner
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 0:50
     */

    self::Fn('Run', function ($Call)
    {
        $Output = array();

        $Prototype = $Call['Call']['Prototype'];
        $Prototype['Revolver'] = array($Call['Call']['Key'] => $Call['Call']['Values']);

        foreach ($Call['Call']['Values'] as $Value)
            $Output[$Value]
                = Code::Run(
                        Code::ConfWalk(
                            $Prototype,
                            array(
                                 $Call['Call']['Key'] => $Value
                                )),
                        $Call['Mode']);

        return $Output;
    });