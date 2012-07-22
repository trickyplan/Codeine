<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Get', function ($Call)
    {
        return $Call['Gravatar']['URL'].md5( strtolower( trim($Call['EMail']) ) ). '?d='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/img/no.jpg');
    });