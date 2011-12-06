<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Clean', function ($Call)
        {
            // Cleanup unused places
            $Call['Output'] = preg_replace('@<place>(.*)</place>@SsUu', '', $Call['Output']);
            return $Call;
        });