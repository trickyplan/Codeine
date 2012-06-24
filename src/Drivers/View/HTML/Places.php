<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Clean', function ($Call)
    {
        // Cleanup unused places
        $Call['Output'] = preg_replace('@<place>(.*)</place>@SsUu', '', $Call['Output']);
        return $Call;
    });