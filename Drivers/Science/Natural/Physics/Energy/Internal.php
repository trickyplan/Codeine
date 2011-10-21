<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 3:46
     */

    self::Fn('Get', function ($Call)
    {
        return $Call['Mass'] * pow(F::Run(
                                      array(
                                          '_N' => 'Science.Physics.Speed.Light',
                                          '_F' => 'Get'
                                      ))
                                  , 2);
    });
