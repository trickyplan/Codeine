<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Pagination hooks 
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeList', function ($Call)
    {
        if (isset($Call['Random']))
        {
            $Call['Elements'] = F::Run('Entity', 'Read', $Call,
                [
                    'Limit' =>
                    [
                        'To' => $Call['Random']
                    ]
                ]);

            if (empty($Call['Elements']))
                ;
            else
            {
                shuffle($Call['Elements']);
                $Call['Elements'] = array_slice($Call['Elements'], 0, $Call['Count']);
            }
        }

        return $Call;
    });