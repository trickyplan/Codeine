<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    setFn('Do', function ($Call)
    {
        $Element = F::Run('Entity', 'Read',
            [
                'Entity' => 'User',
                'Where' =>
                    [
                        $Call['Request']['Key'] => $Call['Request']['Value']
                    ]
            ]);

        $Call['Output']['Content'] = empty($Element);

        return $Call;
    });
