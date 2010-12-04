<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Stringer
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 04.12.10
     * @time 15:06
     */

    self::Fn('Get', function ($Call)
    {
        $Allowed = 'ABCDEFGHKMNPQRSTUVWXYZabcdefghkmnpqrstuvwxyz23458';
        $SZAllow = sizeof($Allowed);
        $Output = '';
        for ($a = 0; $a< 20; $a++)
            $Output.= mb_substr($Allowed,rand(0,$SZAllow),1);
        return $Output;
    });