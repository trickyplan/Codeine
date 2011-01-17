<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: HTTP Basic Auth Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 01.12.10
     * @time 20:28
     */

    self::Fn('Audit', function ($Call)
    {
        if (isset($_SERVER['PHP_AUTH_USER']))
            return true;
        // TODO Audit HTTP
    });

    self::Fn('Register', function ($Call)
    {
        header('WWW-Authenticate: Basic realm="Login"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    });

    self::Fn('Annulate', function ($Call)
    {
        //TODO HTTP Annulate
    });