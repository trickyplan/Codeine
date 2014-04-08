<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if (is_string($Call['Value']))
        {
            $Tags = explode(',', $Call['Value']);

            foreach ($Tags as &$Tag)
                $Tag = trim($Tag);

            $Tags = array_unique($Tags);

            $Call['Value'] = $Tags;
        }

        return $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value'];
    });