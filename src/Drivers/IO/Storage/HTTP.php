<?php

    /* Codeine
    * @author BreathLess
    * @description
    * @package Codeine
    * @version 7.1
    */

    self::setFn('Open', function ($Call)
    {
        return curl_init();
    });

    self::setFn('Read', function ($Call)
    {
        curl_setopt_array($Call['Link'],
          array(
               CURLOPT_HEADER => false,
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_COOKIEJAR => 'cookie.txt',
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_CONNECTTIMEOUT => 15,
               CURLOPT_URL => $Call['Where']['ID']));
        
        return curl_exec($Call['Link']);
    });

    self::setFn('Write', function ($Call)
    {
        $Headers = isset($Call['Headers'])? $Call['Headers']: array();
        $UPWD = isset($Call['user:pass'])? $Call['user:pass']:'';

        // TODO HTTP DELETE

        curl_setopt_array($Call['Link'],
            array(
                CURLOPT_URL => $Call['Where']['ID'],
                CURLOPT_POST => true,
                CURLOPT_COOKIEJAR => 'cookie.txt',
                CURLOPT_HTTPHEADER => $Headers,
                CURLOPT_USERPWD => $UPWD, // FIXME
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $Call['Data']));

        return curl_exec($Call['Link']);
    });

    self::setFn ('Close', function ($Call)
    {
        return curl_close ($Call['Link']);
    });

    self::setFn('Execute', function ($Call)
    {
        return true;
    });
