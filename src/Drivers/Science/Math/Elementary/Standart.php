<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Log', function ($Call)
    {
        return log($Call['X'], F::Live($Call['Base']));
    });