<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
        return json_decode (F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Where' => $Call['UAS']['Host'].'?'.$Call['UAS']['UA Parameter'].'='.urlencode($Call['UA']).'&'.$Call['UAS']['Suffix']
            ])[0],true) ;
    });