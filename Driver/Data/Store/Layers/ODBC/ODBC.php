<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: ODBC Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 22:04
     */

    self::Fn('Connect', function ($Call)
    {
        $Store = odbc_connect($Call['DSN'], $Call['User'], $Call['Password']);
        if (!$Store)
        {
            Code::On('Data', 'errDataODBCCannotConnect', $Call);
            return false;
        }
    });

    self::Fn('Disconnect', function ($Call)
    {
        return odbc_close($Call['Store']);
    });

    self::Fn('Read', function ($Call)
    {
        // FIXME SQL Syntax Driver
        $Result = odbc_do($Call['Store'], $Call['Where']);

        while ($Data[] = odbc_fetch_array($Result));

        return $Data;
    });

    self::Fn('Create', function ($Call)
    {

    });

    self::Fn('Update', function ($Call)
    {
        
    });

    self::Fn('Delete', function ($Call)
    {
        
    });