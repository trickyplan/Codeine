<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Voice Renderer
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 16.11.10
     * @time 3:38
     */

    self::Fn('Render', function ($Call)
    {
        return passthru('echo "'.$Call['Output'].'" | text2wave');
    });