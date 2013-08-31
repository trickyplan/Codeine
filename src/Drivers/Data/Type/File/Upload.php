<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        $Call['Scope'] = strtolower($Call['Entity']);

        if (is_scalar($Call['Value']))
            return $Call['Value'];

        if ($Call['Value']['error'] == 0)
        {
            $Call['ID'] = F::Run('Security.UID', 'Get', $Call);
            $Call['Name'] = F::Live($Call['Node']['Naming'], $Call);

            return F::Run('IO', 'Execute', $Call,
            [
                'Execute' => 'Upload',
                'Storage' => $Call['Node']['Storage']
            ]);
        }
        else
            return null;

    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value'];
    });