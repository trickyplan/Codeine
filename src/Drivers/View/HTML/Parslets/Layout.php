<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet
     * @package Codeine
     * @version 8.x
     */

    setFn('Parse', function ($Call) {
        foreach ($Call['Parsed']['Value'] as $IX => $Match) {
            list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Match]);
            $Call['Replace'][$Call['Parsed']['Match'][$IX]] = F::Run(
                'View',
                'Load',
                $Call,
                ['Scope' => $Asset, 'ID' => $ID]
            );
        }

        return $Call;
    });
