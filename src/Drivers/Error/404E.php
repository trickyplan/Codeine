<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Do', function ($Call)
    {
        if (isset($Call['Entity']))
            $Call['Layouts'][] =
                [
                    'Scope' => $Call['Entity'],
                    'ID' => '404E'
                ];

        $Call['Layouts'][] =
            [
                'Context'   => $Call['Context'],
                'Scope'     => 'Error',
                'ID'        => '404E'
            ];

        $Call['Failure'] = true;
        unset ($Call['Service'], $Call['Method']);

        return $Call;
     });