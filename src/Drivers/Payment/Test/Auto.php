<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        F::Run('Entity', 'Update', $Call,
        [
            'Data' =>
            [
                'Balance' => $Call['Payment']['Value']
            ]
        ]);
        return $Call;
    });