<?php

/* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Detect', function ($Call)
    {
        return (isset($_SERVER['HTTP_DNT']) || isset($_SERVER['X-Do-Not-Track']));
     });