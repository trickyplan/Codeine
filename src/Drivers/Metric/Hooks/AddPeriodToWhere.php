<?php

    setFn('Do', function ($Call) {
        if (isset($Call['Metric']['Since']) || isset($Call['Metric']['Till'])) {
            $Call['Metric']['Since'] = $Call['Metric']['Since'] ?? 0;
            $Call['Metric']['Till'] = $Call['Metric']['Till'] ?? time();

            $Call['Metric']['Since'] = F::Variable($Call['Metric']['Since'], $Call);
            $Call['Metric']['Till'] = F::Variable($Call['Metric']['Till'], $Call);

            $Call['Metric']['Where']['Resolution'] = (int)$Call['Metric']['Resolution'];
            $Call['Metric']['Where']['Time']['$gte'] = floor($Call['Metric']['Since'] / $Call['Metric']['Resolution']);
            $Call['Metric']['Where']['Time']['$lt'] = floor($Call['Metric']['Till'] / $Call['Metric']['Resolution']);
        }

        return $Call;
    });
