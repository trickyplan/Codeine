<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Detection of mysql_query, md5, fopen e.t.c
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 02.12.10
     * @time 0:20
     */

    self::Fn('Detect', function ($Call)
    {
        // FIXME
        if (!isset($Call['Functions']))
            $Call['Functions'] = array('mysql_query', 'md5', 'sha1');
        
        foreach ($Call['Functions'] as $Function)
            if (mb_stripos($Call['Source'], $Function))
                return true;

        return false;
    });