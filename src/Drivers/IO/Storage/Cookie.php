<?php

    /* Codeine
     * @author bergstein@trickyplan.com
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
                    [
                        'expires'   => time() + $Call['TTL'],
                        'path'      => $Call['Path'],
                        'domain'    => $Call['HTTP']['Host'],
                        'secure'    => $Call['Secure'],
                        'httponly'  => $Call['HTTP Only'],
                        'samesite'  => $Call['Same Site']
                    ]))

                    $Call['HTTP']['Cookie'][$Call['Where']['ID']] = $Call['Data'];
                else
                    $Call = F::Hook('Cookie.Set.Failed', $Call);
            }
        else
            setcookie ($Call['HTTP']['Cookie'][$Call['Where']['ID']], '');

        return $Call;
    });