#!/usr/bin/php
<?php

    /**
     * @author BreathLess
     * @time 5:17
     */

    include 'Codeine/Core.php';

    F::Bootstrap();

    $Opts = getopt ('s:m:', array('Service:', 'Method:'));

    F::Run(
        'System.Interface.CLI',
        'Run',
            $Opts
    );

