<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 7.2
     */

    setFn('beforeOperation', function ($Call) {
        if (isset($Call['List']['Sort'])) {
            $Call['Sort'] = $Call['List']['Sort'];
        }

        if (isset($Call['Request']['sort'])) {
            if (F::Dot($Call['Nodes'], $Call['Request']['sort'])) {
                $Call['Sort'] = [$Call['Request']['sort'] => true];
            }
        } elseif (isset($Call['Request']['rsort'])) {
            if (F::Dot($Call['Nodes'], $Call['Request']['rsort'])) {
                $Call['Sort'] = [$Call['Request']['rsort'] => false];
            }
        }

        if (isset($Call['Request']['Sort'])) {
            $Call['Sort'] = [];
            foreach ($Call['Request']['Sort'] as $Key => $Direction) {
                if ($Direction === 'true') {
                    $Direction = true;
                } else {
                    $Direction = false;
                }

                $Call['Sort'][$Key] = $Direction;
            }
        }
        return $Call;
    });
