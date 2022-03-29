<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Console Object Support
     * @package Codeine
     * @version 8.x
     */

    setFn('Open', function ($Call) {
        return fopen('php://stderr', 'a+');
    });

    setFn('Write', function ($Call) {
        return fwrite($Call['Link'], $Call['Data']);
    });

    setFn('Close', function ($Call) {
        return fclose($Call['Link']);
    });