<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if (isset($Call['Value']) && !empty($Call['Value']) && filter_var($Call['Value'], FILTER_VALIDATE_URL))
        {
            $Parts = parse_url($Call['Value']);

            if (!isset($Parts['host']))
            {
                $Parts['host'] = $Parts['path'];
                unset($Parts['path']);
            }

            $Parts['scheme'] = isset($Parts['scheme'])? $Parts['scheme']: 'http';
            $Parts['path'] = isset($Parts['path'])? $Parts['path']:'';
            $Parts['query'] = isset($Parts['query'])? $Parts['query']:'';
            $Parts['fragment'] = isset($Parts['fragment'])? $Parts['fragment']:'';

            return $Parts['scheme'].'://'.$Parts['host'].$Parts['path'].$Parts['query'].$Parts['fragment'];
        }
        else
            return null;
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return (string) $Call['Value'];
    });