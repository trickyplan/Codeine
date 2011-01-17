<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: SAFERPLUS MCrypt Wrapper
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     */

    
    self::Fn('EncryptCBC', function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_CBC);
    });

    self::Fn('DecryptCBC', function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_CBC);
    });

    self::Fn('EncryptCFB', function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_CFB);
    });

    self::Fn('DecryptCFB', function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_CFB);
    });

    self::Fn('EncryptCTR', function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_CTR);
    });

    self::Fn('DecryptCTR', function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_CTR);
    });

    self::Fn('EncryptECB', function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_ECB);
    });

    self::Fn('DecryptECB', function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_ECB);
    });

    self::Fn('EncryptNCFB', function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_NCFB);
    });

    self::Fn('DecryptNCFB', function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_NCFB);
    });

    self::Fn('EncryptNOFB', function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_NOFB);
    });

    self::Fn('DecryptNOFB', function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_NOFB);
    });

    self::Fn('EncryptOFB', function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_OFB);
    });

    self::Fn('DecryptOFB', function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_OFB);
    });

    self::Fn('EncryptSTREAM', function ($Call)
    {
        return mcrypt_encrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_STREAM);
    });

    self::Fn('DecryptSTREAM', function ($Call)
    {
        return mcrypt_decrypt(MCRYPT_SAFERPLUS, $Call['Key'], $Call['Input'], MCRYPT_MODE_STREAM);
    });
