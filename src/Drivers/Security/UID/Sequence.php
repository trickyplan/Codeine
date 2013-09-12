<?php

    /* Codeine
     * @author BreathLess
     * @description: Random integer
     * @package Codeine
     * @version 7.x
     * @date 04.12.10
     * @time 14:56
     */

    setFn('Get', function ($Call)
    {
        return F::Run('IO', 'Execute', ['Storage' => $Call['Storage'], 'Scope' => $Call['Entity'], 'Execute' => 'ID']);
    });
