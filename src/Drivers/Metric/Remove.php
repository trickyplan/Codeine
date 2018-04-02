<?php

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('afterMetricRemove', $Call);

            //$Call = F::Apply('Metric.Calc', 'Where', $Call);

            $Call['Data'] = F::Run('IO', 'Write', $Call,
                [
                    'Storage'   => 'Primary',
                    'Scope'     => 'Metric',
                    'Where'     => $Call['Where']
                ]);

        $Call = F::Hook('beforeMetricRemove', $Call);

        return $Call['Data'];
    });