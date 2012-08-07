<?php

    /* Codeine
     * @author BreathLess
     * @description: Фронт контроллер
     * @package Codeine
     * @version 7.6.2
     * @date 31.08.11
     * @time 1:12
     */

    self::setFn('Run', function ($Call)
    {
      $Call = F::Live($Call['Apps'][$Call['Step']], $Call);

      return $Call;
    });
