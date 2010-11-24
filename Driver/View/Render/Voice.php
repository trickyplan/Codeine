<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Voice Renderer
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 16.11.10
     * @time 3:38
     */

    $Render = function ($Call)
    {
        exec ('echo "'.$Call['Output'].'" | text2wave', $contents);
        return $contents;
    };