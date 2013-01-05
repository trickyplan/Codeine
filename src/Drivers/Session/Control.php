<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::_loadSource('Entity.Control');

    setFn('Flush', function ($Call)
    {
        $Call = F::Run('Entity', 'Delete', $Call, ['Entity' => 'Session', 'Where' => ['Expire' => ['<' => time()], ['ID' => ['<>' => $Call['SID']]]]]);
        return F::Hook('afterDeletePost', $Call);
    });