<?php

  /* Codeine
     * @author BreathLess
     * @description  MySQL Driver
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Open', function ($Call)
    {
        $Link = new mysqli($Call['Server'], $Call['User'], F::Live($Call['Password']));

        if ($Link->errno != 0)
            return $Link->error;

        $Link->select_db ($Call['Database']);
        $Link->set_charset ($Call['Charset']);
     //   $Link->autocommit ($Call['AutoCommit']);

        return $Link;
    });

    self::setFn ('Read', function ($Call)
    {
        $Query = F::Run('IO.Syntax.MySQL', 'Read', $Call);

        $Result = $Call['Link']->query($Query);

        if (isset($Call['Debug']))
            d(__FILE__, __LINE__, $Query);

        $Data = array();

        while($Row = $Result->fetch_assoc())
            $Data[] = $Row;

        return $Data;
    });

    self::setFn ('Write', function ($Call)
    {
        if (isset($Call['Where']))
            $Query = F::Run('IO.Syntax.MySQL', 'Update', $Call);
        else
            $Query = F::Run('IO.Syntax.MySQL', 'Insert', $Call);

        if (isset($Call['Debug']))
            d(__FILE__, __LINE__, $Query);

        $Result = $Call['Link']->query($Query);

        return $Result;
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {
        return $Call['Link']->query($Call['Execute']);
    });