<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        F::Run('IO', 'Write', $Call['Node'], array('Scope' => $Call['Entity'], 'Where' => $Call['Data']['ID'], 'Data' => array($Call['Name'] => $Call['Value'])));

        return null;
    });

    setFn(['Read','Where'], function ($Call)
    {
        return F::Run('IO', 'Read', $Call['Node'], array('Scope' => $Call['Entity'], 'Where' => $Call['Data']['ID']))[0][$Call['Name']];
    });