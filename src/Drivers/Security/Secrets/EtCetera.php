<?php

    /* Codeine
     * @author BreathLess
     * @description EtCetera Loader 
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Password', function ($Call)
    {
        return trim(file_get_contents('/etc/codeine/secrets/'.$Call['ID'].'.password'));
    });