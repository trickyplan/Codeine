<?php

    /**
     * @author BreathLess
     * @type ${Type}
     * @description: ${Description}
     * @package ${Package}
     * @subpackage ${Subpackage}
     * @version ${Version}
     * @date 13.03.11
     * @time 21:53
     */

    $Call = 'System.Date.Time::Get()';
    preg_match_all('/([\S]+)::([\S]+)\((.*)\)/', $Call, $Matches);

    print_r($Matches);
