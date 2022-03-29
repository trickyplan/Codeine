<?php

    setFn('Convert', function ($Call) {
        return F::Run(
            'Format.' . $Call['Format']['Output'],
            'Write',
            $Call,
            [
                'Value' => F::Run('Format.' . $Call['Format']['Input'], 'Read', $Call)
            ]
        );
    });
