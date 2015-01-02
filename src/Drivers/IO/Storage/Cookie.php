<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */
    setFn('Open', function ($Call)
    {
        return $Call;
    });

    setFn ('Read', function ($Call)
    {
        if (PHP_SAPI == 'cli')
            return null;

        return isset($Call['HTTP']['Cookie'][$Call['Where']['ID']]) ? $Call['HTTP']['Cookie'][$Call['Where']['ID']]: null;
    });

    setFn ('Write', function ($Call)
    {
        if (null !== $Call['Data'])
            {
                if (setcookie ($Call['Where']['ID'],
                    $Call['Data'],
                    time() +
                    $Call['TTL'],
                    $Call['Path'],
                    $Call['HTTP']['Host'],
                    $Call['Secure'],
                    $Call['HTTP Only']))

                    $Call['HTTP']['Cookie'][$Call['Where']['ID']] = $Call['Data'];
                else
                    $Call = F::Hook('Cookie.Set.Failed', $Call);
            }
        else
            setcookie ($Call['HTTP']['Cookie'][$Call['Where']['ID']], '');

        return $Call;
    });