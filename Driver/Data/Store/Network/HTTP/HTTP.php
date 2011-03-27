<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: HTTP Store Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 21:39
     */

    self::Fn('Connect', function ($Call)
    {
        return curl_init();
    });

    self::Fn('Disconnect', function ($Call)
    {
        curl_close($Call['Store']);
    });

    self::Fn('Read', function ($Call)
    {
        curl_setopt_array($Call['Store'],
          array(
               CURLOPT_HEADER => false,
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_CONNECTTIMEOUT => 15,
               CURLOPT_URL => $Call['Data']['Where']['ID']));
        
        return curl_exec($Call['Store']);
    });

    self::Fn('Create', function ($Call)
    {
        $Call['Link'] = curl_init();
        curl_setopt_array($Call['Link'],
            array(
                CURLOPT_URL => $Call['ID'],
                CURLOPT_POST => true,
                CURLOPT_HEADER => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $Call['Data']));

        return curl_exec($Call['Link']);
    });

    self::Fn('Update', function ($Call)
    {
        // TODO HTTP Update
    });

    self::Fn('Delete', function ($Call)
    {
        // TODO HTTP Delete
    });
