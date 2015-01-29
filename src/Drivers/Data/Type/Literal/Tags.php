<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if (is_string($Call['Value']))
        {
            $Tags = explode(',', $Call['Value']);

            foreach ($Tags as &$Tag)
                $Tag = trim($Tag);

            $Call['Value'] = $Tags;
        }

        $Call['Value'] = array_unique($Call['Value']);

        return $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value'];
    });