<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Clean', function ($Call)
    {
        // Cleanup unused places
        $Call['Output'] = preg_replace('@<place>(.*)</place>@SsUu', '', $Call['Output']);
        return $Call;
    });