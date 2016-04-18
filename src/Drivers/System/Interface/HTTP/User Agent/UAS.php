<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        return jsond_decode (F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Where' => $Call['UAS']['Host'].'?'.$Call['UAS']['UA Parameter'].'='.urlencode($Call['HTTP']['Agent']).'&'.$Call['UAS']['Postfix']
            ])[0],true) ;
    });