<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Write', function ($Call)
    {
        if (is_scalar($Call['Value']))
            return $Call['Value'];

        $Call['ID'] = $Call['Data']['ID'];
        $Call['Name'] = F::Live($Call['Node']['Naming'], $Call);

        return F::Run('IO', 'Execute', $Call,
        [
            'Execute' => 'Upload',
            'Storage' => $Call['Node']['Storage']
        ]);
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });