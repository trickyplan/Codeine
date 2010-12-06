<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Artificial Neural Network Architecture  (PoC)
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 06.12.10
     * @time 13:27
     */

    self::Fn('Run', function ($Call)
    {
        $Neurons = $Call['Neurons'];
        $Data = array();

        foreach ($Neurons['Input'] as $Name => $Input)
        {
            $NeuroCall = array_merge($Input['Call'], array('Input' => $Call['Data'][$Name]));
            $Data[$Name] = Code::Run($NeuroCall);
        }

        foreach ($Neurons['Output'] as $Name => $Input)
        {
            $NeuroCall = array_merge($Input['Call'], $Data);
            $Data[$Name] = Code::Run($NeuroCall);
        }

        return $Data;
    });