<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Block Element
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 22.11.10
     * @time 6:06
     */

    self::Fn('Make', function ($Call)
    {
        if (isset($Call['Item']['Class']))
            $Call['Item']['Class'] = implode(' ',$Call['Item']['Class']);
        else
            $Call['Item']['Class'] = '';

        return '<div class="Badge '.$Call['Item']['Class'].'">'.$Call['Item']['Data'].'</div>';
    });