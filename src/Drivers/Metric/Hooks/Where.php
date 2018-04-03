<?php

    setFn('Do', function ($Call)
    {
        if (isset($Call['Where']))
            ;
        else
            $Call['Where'] = [];

        if (isset($Call['Metric']['Dimensions']))
        {
            $Call['Where'] = F::Merge($Call['Where'], $Call['Metric']['Dimensions']);
            F::Log(function () use ($Call) {return 'Metric Dimensions: *'.j($Call['Where']).'*';} , LOG_INFO);
        }

        if (isset($Call['Metric']['Resolutions']))
        {
            $Call['Where']['Resolution'] = $Call['Metric']['Resolutions'];
            F::Log(function () use ($Call) {return 'Resolutions: *'.j($Call['Metric']['Resolutions']).'*';} , LOG_INFO);
        }

        $Call['Where']['Type'] = $Call['Metric']['Type'];
        F::Log(function () use ($Call) {return 'Metric Type: *'.$Call['Where']['Type'].'*';} , LOG_INFO);

        return $Call;
    });
