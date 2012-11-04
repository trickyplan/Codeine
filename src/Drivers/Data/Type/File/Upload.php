<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        if (is_scalar($Call['Value']))
            return $Call['Value'];

        $Call['ID'] = F::Run('Security.UID', 'Get',$Call);
        $Call['Name'] = F::Live($Call['Node']['Naming'], $Call);

        return F::Run('IO', 'Execute', $Call,
        [
            'Execute' => 'Upload',
            'Storage' => $Call['Node']['Storage']
        ]);
    });

    setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });