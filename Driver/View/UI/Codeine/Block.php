<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Block Element
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 22.11.10
     * @time 6:06
     */

    self::Fn('Make', function ($Call)
    {
        if (isset($Call['Class']))
            $Call['Class'] = implode(' ',$Call['Class']);
        else
            $Call['Class'] = '';

        return '<div class="Block '.$Call['Class'].'">'.$Call['Item']['Data'].'</div>';
    });