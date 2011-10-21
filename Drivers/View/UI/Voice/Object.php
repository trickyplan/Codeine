<?php

    /* Codeine
     * @author BreathLess
     * @description: Object Templater Codeine
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Make', function ($Call)
    {
        return $Call['Item']['Data'][$Call['Item']['ID']]['Title'].' '.$Call['Item']['Data'][$Call['Item']['ID']]['Text'];
    });
