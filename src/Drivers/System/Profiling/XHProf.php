<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Start', function ($Call)
    {
        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
        return $Call;
    });

    setFn('Finish', function ($Call)
    {
        $xhprof_data = xhprof_disable();
        include_once "xhprof_lib/utils/xhprof_lib.php";
        include_once "xhprof_lib/utils/xhprof_runs.php";
        $xhprof_runs = new XHProfRuns_Default();
        $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_test");

        F::Log('<a href="http://xhprof/index.php?run='.$run_id.'&source=xhprof_test">Report</a>');
        return $Call;
    });