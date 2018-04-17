<?php

setFn('Do', function ($Call) {
    if (isset($Call['Since']) || isset($Call['Till'])) {
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

        $Call['Where']['Time']['$gte'] = $Call['Since'] / $Call['Where']['Resolution'];
        $Call['Where']['Time']['$lte'] = $Call['Till'] / $Call['Where']['Resolution'];
    }

    return $Call;
});