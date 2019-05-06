<?php

    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Metric']['Since']) || isset($Call['Metric']['Till']))
        {
            $Call['Metric']['Since'] = $Call['Metric']['Since'] ?? 0;
            $Call['Metric']['Till'] = $Call['Metric']['Till'] ?? time();

            $Call['Metric']['Since'] = F::Variable($Call['Metric']['Since'], $Call);
            $Call['Metric']['Till'] = F::Variable($Call['Metric']['Till'], $Call);

            $Period = $Call['Metric']['Till'] - $Call['Metric']['Since'];
    
            if (!isset($Call['Metric']['Resolutions'])) {
                switch (true) {
                    case $Period > 86400:
                        $Call['Metric']['Resolutions'] = [86400];
                        break;
                    case $Period > 3600;
                        $Call['Metric']['Resolutions'] = [3600];
                        break;
                    default:
                        $Call['Metric']['Resolutions'] = [60];
                }
            }
            $Resolution = array_pop($Call['Metric']['Resolutions']);
            
            $Call['Metric']['Where']['Time']['$gte'] = floor($Call['Metric']['Since'] / $Resolution);
            $Call['Metric']['Where']['Time']['$lt'] = floor($Call['Metric']['Till'] / $Resolution);
        }

        return $Call;
    });