<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if ($Call['Data']['Target'] == $Call['Session']['User']['ID'])
            F::Run('Entity', 'Update', $Call, ['One' => true, 'Data!' => ['Read' => time()]]);

        return $Call;
    });