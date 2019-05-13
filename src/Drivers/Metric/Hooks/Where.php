<?php

    setFn('Do', function ($Call)
    {
        if (isset($Call['Metric']['Where']))
            ;
        else
            $Call['Metric']['Where'] = [];

        if (isset($Call['Metric']['Dimensions']))
        {
            $Call['Metric']['Where'] = F::Merge($Call['Metric']['Where'], $Call['Metric']['Dimensions']);
            $Call['Metric']['Where'] = jd(j($Call['Metric']['Where']));
            F::Log(function () use ($Call) {return 'Metric Dimensions: *'.j($Call['Metric']['Where']).'*';} , LOG_INFO);
        }

        if (isset($Call['Metric']['Resolutions']))
        {
            $Call['Metric']['Where']['Resolution'] = $Call['Metric']['Resolutions'];
            F::Log(function () use ($Call) {return 'Resolutions: *'.j($Call['Metric']['Resolutions']).'*';} , LOG_INFO);
        }

        $Call['Metric']['Where']['Type'] = $Call['Metric']['Type'];
        F::Log(function () use ($Call) {return 'Metric Type: *'.$Call['Metric']['Where']['Type'].'*';} , LOG_INFO);

        return $Call;
    });
