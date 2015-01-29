<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */


    setFn('Get', function ($Call)
    {
        $Call['Value'] = strtr(mb_strtolower(preg_replace('/([А-яё])\\1+/','', $Call['Value'])), $Call['Replace']);
        return preg_replace('/([А-яё])\\1+/SsUu', '\1', $Call['Value']);
    });