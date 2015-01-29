<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     * @date 31.08.11
     * @time 6:17
     */

    setFn('Route', function ($Call)
    {
        if (strpos($Call['Run'], '?'))
            list($Call['Run']) = explode('?', $Call['Run']);

        if (isset($Call['Links']))
        {
            if (is_string($Call['Run']) && isset($Call['Links'][$Call['Run']]))
            {
                if (isset($Rule['Debug']) && $Rule['Debug'] === true)
                    d(__FILE__, __LINE__, $Rule);

                $Call['Run'] = $Call['Links'][$Call['Run']];
            }
        }

        unset($Call['Links']);
        return $Call;
    });

    setFn('Reverse', function ($Call)
    {
        if (isset($Call['Links']))
        {
            foreach ($Call['Links'] as $Link => $Run)
            {
                if (F::Diff($Call['Run'], $Run) == null)
                    $Call['Link'] = $Link;
            }
        }

        return $Call;
    });