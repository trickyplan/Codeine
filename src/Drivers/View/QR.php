<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call) {
        $Call = F::Apply(
            'View.QR.' . F::Dot($Call, 'View.QR.Renderer'),
            'Encode',
            $Call,
            [
                'QR' =>
                    [
                        'Data' => $Call['Data'],
                        'Size' => F::Dot($Call, 'View.QR.Size')
                    ]
            ]
        );

        return $Call;
    });
