<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2020.x.x
     */

    define('KELVIN_SHIFT', 273.15);

    setFn('Do', function ($Call) {
        return F::Run(null, $Call['From'] . '.' . $Call['To'], $Call);
    });

    setFn('Kelvin.Celsius', function ($Call) {
        return $Call['Value'] - KELVIN_SHIFT;
    });

    setFn('Kelvin.Fahrenheit', function ($Call) {
        return (9 / 5 * ($Call['Value'] - KELVIN_SHIFT)) + 32;
    });

    setFn('Celsius.Kelvin', function ($Call) {
        return $Call['Value'] + KELVIN_SHIFT;
    });

    setFn('Celsius.Fahrenheit', function ($Call) {
        return (9 / 5 * $Call['Value']) + 32;
    });

    setFn('Fahrenheit.Celsius', function ($Call) {
        return (5 / 9 * $Call['Value']) - 32;
    });

    setFn('Fahrenheit.Kelvin', function ($Call) {
        return 9 / 5 * ($Call['Value'] - KELVIN_SHIFT) + 32;
    });
