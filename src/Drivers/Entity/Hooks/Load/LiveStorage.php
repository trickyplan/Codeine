<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2020.x.x
     */

    setFn('afterEntityLoad', function ($Call) {
        $Call['Storage'] = F::Live($Call['Storage'], $Call);
        return $Call;
    });
