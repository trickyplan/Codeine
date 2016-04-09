<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Start', function ($Call)
    {
        $Call['Tabs'] = [];
        return $Call;
    });

    setFn('Add', function ($Call)
    {
        if (isset($Call['Widget']['Tab']))
        {
            $Call['Tabs'][$Call['Widget']['Tab']] = $Call['Widget']['Tab'];
            $Call['Output'][$Call['Widget']['Tab']][] = $Call['Widget'];
        }

        return $Call;
    });

    setFn('Finish', function ($Call)
    {
        $Call['Output']['Form'] =
            [
                [
                    'Type'      => 'Tabbable',
                    'Options'   => $Call['Tabs']
                ]
            ];

        return $Call;
    });