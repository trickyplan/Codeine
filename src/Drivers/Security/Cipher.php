<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Security.Cipher
     * @package Codeine
     * @version 12.x
     * @date 28.12.2017
     * @time 10.59
     */

    setFn('Encode', function ($Call)
    {
        return F::Apply('Security.Cipher.'.F::Dot($Call, 'Security.Cipher.Driver'), null, $Call);
    });
    
    setFn('Decode', function ($Call)
    {
        return F::Apply('Security.Cipher.'.F::Dot($Call, 'Security.Cipher.Driver'), null, $Call);
    });
    
    setFn('Symmetrical Encode', function ($Call)
    {
        return F::Apply('Security.Cipher.'.F::Dot($Call, 'Security.Cipher.Driver'), null, $Call);
    });
    
    setFn('Symmetrical Decode', function ($Call)
    {
        return F::Apply('Security.Cipher.'.F::Dot($Call, 'Security.Cipher.Driver'), null, $Call);
    });