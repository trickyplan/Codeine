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
        if (is_string($Call['Run']))
        {
            if (mb_strpos($Call['Run'], '?'))
                list($Call['Run']) = explode('?', $Call['Run']);
    
            if (isset($Call['Rewrite']))
            {
                if (is_string($Call['Run']) && isset($Call['Rewrite'][$Call['Run']]))
                {
                    $Call['Run'] =
                        [
                            'Service' => 'System.Interface.HTTP',
                            'Method' => 'Redirect',
                            'Call' =>
                                [
                                    'Redirect' => $Call['Rewrite'][$Call['Run']]
                                ]
                        ];
                }
            }
        }

        return $Call;
    });

    setFn('Reverse', function ($Call)
    {
        return $Call;
    });