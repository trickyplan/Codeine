<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Company'] = F::Run('Entity', 'Read', ['Entity' => 'Company', 'Where' => 1, 'One' => true]);
        return $Call;
    });