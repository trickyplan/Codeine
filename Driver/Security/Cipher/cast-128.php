<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: CAST_128 MCrypt Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     */

    
    $EncryptCBC = function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_CBC);
    };

    $DecryptCBC = function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_CBC);
    };

    $EncryptCFB = function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_CFB);
    };

    $DecryptCFB = function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_CFB);
    };

    $EncryptCTR = function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_CTR);
    };

    $DecryptCTR = function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_CTR);
    };

    $EncryptECB = function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_ECB);
    };

    $DecryptECB = function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_ECB);
    };

    $EncryptNCFB = function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_NCFB);
    };

    $DecryptNCFB = function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_NCFB);
    };

    $EncryptNOFB = function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_NOFB);
    };

    $DecryptNOFB = function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_NOFB);
    };

    $EncryptOFB = function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_OFB);
    };

    $DecryptOFB = function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_OFB);
    };

    $EncryptSTREAM = function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_STREAM);
    };

    $DecryptSTREAM = function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_CAST_128, $Call['Key'], $Call['Input'], MCRYPT_MODE_STREAM);
    };
