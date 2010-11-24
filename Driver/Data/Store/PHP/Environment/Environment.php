<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: ENV Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 24.11.10
     * @time 21:25
     */

    self::Fn('Connect', function ($Call)
    {
        return isset($_ENV);
    });

    self::Fn('Disconnect', function ($Call)
    {
        return isset($_ENV);
    });

    self::Fn('Read', function ($Call)
    {
        if (isset($_ENV[$Call['Data']['Where']['ID']]))
            return $_ENV[$Call['Data']['Where']['ID']];
        else
            return null;
    });

    self::Fn('Create', function ($Call)
    {
        return $_ENV[$Call['Data']['ID']] = $Call['Data']['Data'];
    });

    self::Fn('Update', function ($Call)
    {
        return $_ENV[$Call['Data']['ID']] = $Call['Data']['Data'];
    });

    self::Fn('Delete', function ($Call)
    {
        if (isset($_ENV[$Call['Data']['Where']['ID']]))
        {
            unset ($_ENV[$Call['Data']['Where']['ID']]);
            return true;
        }
        else
            return null;
    });