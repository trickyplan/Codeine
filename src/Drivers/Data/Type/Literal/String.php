<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if (is_scalar($Call['Value']))
        {
            if (mb_strlen($Call['Value']) > $Call['Literal']['String']['Write Size'])
                return mb_substr((string) $Call['Value'], 0, $Call['Literal']['String']['Write Size']);
            else
                return (string) $Call['Value'];
        }
        else
        {
            F::Log('Value not a string: '.j($Call['Value']), LOG_WARNING);
            return null;
        }
    });

    setFn('Where', function ($Call)
    {
//        if (is_string($Call['Value']))
        if (is_scalar($Call['Value']))
            return (string) htmlspecialchars($Call['Value']);
        else
             return null;
    });

    setFn('Read', function ($Call)
    {
//        if (is_string($Call['Value']))
        if (is_scalar($Call['Value']))
        {
            if (mb_strlen($Call['Value']) > $Call['Literal']['String']['Read Size'])
                return mb_substr((string) $Call['Value'], 0, $Call['Literal']['String']['Read Size']);
            else
                return (string) $Call['Value'];
        }
        else
             return null;
    });
