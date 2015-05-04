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
        if (isset($Call['Static']))
            ;
        else
        {
            $Call['Static'] = $Call['Links']; // FIXME
            F::Log('«Links» now «Static», please replace', LOG_WARNING);
        }
        
        if (strpos($Call['Run'], '?'))
            list($Call['Run']) = explode('?', $Call['Run']);

        if (isset($Call['Static']))
        {
            if (is_string($Call['Run']) && isset($Call['Static'][$Call['Run']]))
            {
                if (isset($Rule['Debug']) && $Rule['Debug'] === true)
                    d(__FILE__, __LINE__, $Rule);

                $Call['Run'] = $Call['Static'][$Call['Run']];
            }
        }

        unset($Call['Static']);
        return $Call;
    });

    setFn('Reverse', function ($Call)
    {
        if (isset($Call['Static']))
        {
            foreach ($Call['Static'] as $Link => $Run)
            {
                if (F::Diff($Call['Run'], $Run) == null)
                    $Call['Link'] = $Link;
            }
        }

        return $Call;
    });