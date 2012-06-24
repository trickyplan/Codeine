<?php

    /* Codeine
     * @author BreathLess
     * @description: Фронт контроллер
     * @package Codeine
     * @version 7.4.5
     * @date 31.08.11
     * @time 1:12
     */

    self::setFn('Run', function ($Call)
    {
       foreach ($Call['Apps'] as $Application)
           $Call = F::Live($Application, $Call);

       return $Call;
    });
