<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if ($Call['Data']['Target'] == $Call['Session']['User']['ID'])
            F::Run('Entity', 'Update', $Call, ['One' => true, 'Data!' => ['Readed' => time()]]);

        return $Call;
    });