<?php

    setFn('beforeRequestRun', function ($Call) {
        return $Call;
    });

    setFn('beforeCLIRequestRun', function ($Call) {
        return $Call;
    });