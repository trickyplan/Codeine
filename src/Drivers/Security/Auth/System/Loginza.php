<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Identificate', function ($Call)
    {

    });

    self::setFn('Authenticate', function ($Call)
    {
        d(__FILE__, __LINE__, F::Run('IO', 'Read',
         array(
             'Storage' => 'Web',
             'Where' => 'http://loginza.ru/api/authinfo?token='.$Call['token'].'&id=24623&sig='.md5($Call['token'].'1dc118bc34d783e2117a7b826fccbe2a')
         )));;

die();
        return $Call;
    });