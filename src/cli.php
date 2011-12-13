#!/usr/bin/php
<?php

    /**
     * @author BreathLess
     * @date 27.08.11
     * @time 5:17
     */

    include 'codeine/Core.php';

    F::Bootstrap();

    $Opts = getopt ('s:m:', array('Service:', 'Method:'));

    F::Run(
        'System.Interface.CLI',
        'Run',
            $Opts
    );

