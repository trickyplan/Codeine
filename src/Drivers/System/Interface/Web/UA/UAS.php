<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Get', function ($Call)
    {
        return json_decode (F::Run('IO', 'Read', array(
                'Storage' => 'Web',
                'Where' => $Call['UAS']['Host'].'?'.$Call['UAS']['UA Parameter'].'='.urlencode($Call['UA']).'&'.$Call['UAS']['Suffix']
            ))[0],true) ;
    });