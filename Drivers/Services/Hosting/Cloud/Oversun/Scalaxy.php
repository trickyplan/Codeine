<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 20.02.11
     * @time 19:34
     */

    self::Fn('Instance.Start', function ($Call)
    {
        return shell_exec('curl -u ' . $Call['Username'] . ':\'' . $Call['Password'] . '\' -X PUT -d "" https://www.scalaxy.ru/api/projects/' . $Call['Project'] . '/instances/' . $Call['Instance'] . '/run.json');
    });

    self::Fn('Instance.Stop', function ($Call)
    {
        return shell_exec('curl -u ' . $Call['Username'] . ':\'' . $Call['Password'] . '\' -X PUT -d "" https://www.scalaxy.ru/api/projects/' . $Call['Project'] . '/instances/' . $Call['Instance'] . '/terminate.json');
    });

    self::Fn('Instance.Create', function ($Call)
    {

    });

    self::Fn('Instance.Get', function ($Call)
    {

    });

    self::Fn('Instance.Scale', function ($Call)
    {

    });

    self::Fn('Instance.List', function ($Call)
    {

    });

    self::Fn('Project.Create', function ($Call)
    {

    });

    self::Fn('Project.Get', function ($Call)
    {

    });

    self::Fn('Project.Scale', function ($Call)
    {

    });

    self::Fn('Project.List', function ($Call)
    {

    });
