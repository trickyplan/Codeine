<?php

    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Since']) || isset($Call['Till']))
        {
            $Call['Since'] = $Call['Since'] ?? 0;
            $Call['Till'] = $Call['Till'] ?? time();
            
            $Period = $Call['Till'] - $Call['Since'];
    
            if (!isset($Call['Where']['Resolution'])) {
                switch (true) {
                    case $Period > 86400:
                        $Call['Where']['Resolution'] = 86400;
                        break;
                    case $Period > 3600;
                        $Call['Where']['Resolution'] = 3600;
                        break;
                    default:
                        $Call['Where']['Resolution'] = 60;
                }
            }
            $Resolution = array_pop($Call['Where']['Resolution']);
            
            $Call['Where']['Time']['$gte'] = floor($Call['Since'] / $Resolution);
            $Call['Where']['Time']['$lte'] = floor($Call['Till'] / $Resolution);
        }
    
        return $Call;
    });