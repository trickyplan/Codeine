<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        F::Run('Security.Auth', 'Detach', $Call);

        $Call['Output']['Content'][]
                = array(
                'Type' => 'Block',
                'Class' => 'alert alert-success',
                'Value' => 'Successful exit'
            );

        return $Call;

    });