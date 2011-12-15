<?php

  /* Codeine
     * @author BreathLess
     * @description  MySQL Driver
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Open', function ($Call)
    {
        $Link = new mysqli($Call['Server'], $Call['User'], $Call['Password']);

        if ($Link->errno != 0)
            return $Link->error;

        $Link->select_db ($Call['Database']);
     //   $Link->set_charset ($Call['Charset']);
     //   $Link->autocommit ($Call['AutoCommit']);

        return $Link;
    });

    self::setFn ('Read', function ($Call)
    {
        return 'Pong!';
    });

    self::setFn ('Write', function ($Call)
    {
        return $Call['Data'];
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {
        return true;
    });