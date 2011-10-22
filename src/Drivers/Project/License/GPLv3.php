<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Generate', function ($Call)
        {
            return file_get_contents ('http://www.gnu.org/licenses/gpl.txt');
        });