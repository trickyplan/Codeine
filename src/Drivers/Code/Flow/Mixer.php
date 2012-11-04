<?php

    /* Codeine
     * @author BreathLess
     * @description: Фронт контроллер
     * @package Codeine
     * @version 7.x
     * @date 31.08.11
     * @time 1:12
     */

    setFn('Run', function ($Call)
    {
       foreach ($Call['Apps'] as $Application)
           $Call = F::Live($Application, $Call);

       return $Call;
    });
