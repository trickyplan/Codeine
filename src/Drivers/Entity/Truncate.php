<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeTruncateDo', $Call);

        $Call['Where'] = ['Expire' => ['$lt' => time()]];

            F::Run('Entity', 'Delete', $Call);

        $Call = F::Hook('afterTruncatePost', $Call);

        $Call['Output'][] = true;

        return $Call;
    });