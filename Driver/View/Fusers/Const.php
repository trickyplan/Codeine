<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Key Fuser
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 18.11.10
     * @time 5:51
     */

    self::Fn('Fusion', function ($Call)
    {
        $Consts = array('ID', 'Entity','Plugin');

        $Matches = array();
        $Values = array();

        foreach ($Consts as $Const)
        {
            $Matches[] = '<'.$Const.'/>';
            $Values[] = $Call['Item'][$Const];
        }

        $Call['Body'] = str_replace($Matches, $Values , $Call['Body']);
        return $Call['Body'];
    });
