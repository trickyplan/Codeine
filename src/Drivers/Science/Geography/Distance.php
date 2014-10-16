<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Calc', function ($Call)
    {
        // http://kobzarev.com/programming/calculation-of-distances-between-cities-on-their-coordinates.html
        // перевести координаты в радианы
        $Call['From']['Lat'] = $Call['From']['Lat'] * M_PI / 180;
        $Call['To']['Lat'] = $Call['To']['Lat'] * M_PI / 180;
        $Call['From']['Long'] = $Call['From']['Long'] * M_PI / 180;
        $Call['To']['Long'] = $Call['To']['Long'] * M_PI / 180;

        // косинусы и синусы широт и разницы долгот
        $cl1 = cos($Call['From']['Lat']);
        $cl2 = cos($Call['To']['Lat']);
        $sl1 = sin($Call['From']['Lat']);
        $sl2 = sin($Call['To']['Lat']);
        $delta = $Call['To']['Long'] - $Call['From']['Long'];
        $cdelta = cos($delta);
        $sdelta = sin($delta);

        // вычисления длины большого круга
        $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
        $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

        //
        $ad = atan2($y, $x);
        $dist = ($ad * $Call['Earth']['Radius'])/1000;

        return $dist;
     });