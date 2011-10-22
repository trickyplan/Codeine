<?php

    /* Codeine
     * @author BreathLess
     * @description Git Wrapper 
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Init', function ($Call)
        {
            return shell_exec ('git init'); // TODO Bare Support
        });

    self::Fn ('Commit.All', function ($Call)
        {
            return shell_exec ('git commit -a -m "'.$Call['Message'].'"');
        });

    self::Fn ('Add.All', function ($Call)
        {
            return shell_exec ('git add .');
        });