<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeTruncateDo', $Call);

            F::Run('Entity', 'Delete', $Call, ['Where' => ['Expire' => ['<' => time()]]]);

        $Call = F::Hook('afterTruncatePost', $Call);

        $Call['Output'][] = true;

        return $Call;
    });