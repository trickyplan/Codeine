<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: BC version of standart operations
     * @package Science.Math.Algebra
     * @subpackage Standart
     * @version 5.0
     * @date 28.10.10
     * @time 1:46
     */


    self::Fn('SquareRoot', function ($Call)
    {
        return bcsqrt($Call['Input']);
    });
