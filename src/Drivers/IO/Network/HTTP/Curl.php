<?php

    /* Codeine
    * @author BreathLess
    * @description
    * @package Codeine
    * @version 7.1
    */

    setFn('Open', function ($Call)
    {
        return true;
    });

    setFn('Read', function ($Call)
    {
        $Return = null;

        if (is_array($Call['Where']['ID']))
        {
            $Call['Link'] = curl_multi_init();

            $Links = array();

            foreach ($Call['Where']['ID'] as $cID)
            {
                $Links[$cID] = curl_init($cID);

                curl_setopt_array($Links[$cID],
                  array(
                       CURLOPT_HEADER => false,
                       CURLOPT_RETURNTRANSFER => true,
                       CURLOPT_COOKIEJAR => $Call['CookieFile'],
                       CURLOPT_FOLLOWLOCATION => $Call['Follow'],
                       CURLOPT_CONNECTTIMEOUT => $Call['Timeout']));

                curl_multi_add_handle($Call['Link'], $Links[$cID]);
            }

            $Running = null;

            do
                curl_multi_exec($Call['Link'], $Running);
            while ($Running > 0);

             foreach ($Links as $ID => $Link)
             {
                $Return[$ID] = curl_multi_getcontent($Link);
                curl_multi_remove_handle($Call['Link'], $Link);
             }

             curl_multi_close($Call['Link']);
        }
        else
        {
            $Call['Link'] = curl_init($Call['Where']['ID']);

            curl_setopt_array($Call['Link'],
                array(
                    CURLOPT_HEADER => false,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_COOKIEJAR => $Call['CookieFile'],
                    CURLOPT_FOLLOWLOCATION => $Call['Follow'],
                    CURLOPT_CONNECTTIMEOUT => $Call['Timeout']));

            $Return = array(curl_exec($Call['Link']));

            curl_close($Call['Link']);
        }

        return $Return;
    });

    setFn('Write', function ($Call)
    {
        $Call['Link'] = curl_init($Call['Where']['ID']);

        $Headers = isset($Call['Headers'])? $Call['Headers']: array();
        $UPWD = isset($Call['user:pass'])? $Call['user:pass']:'';

        // TODO HTTP DELETE

        curl_setopt_array($Call['Link'],
            array(
                CURLOPT_URL => $Call['Where']['ID'],
                CURLOPT_POST => true,
                CURLOPT_COOKIEJAR => $Call['CookieFile'],
                CURLOPT_FOLLOWLOCATION => $Call['Follow'],
                CURLOPT_CONNECTTIMEOUT => $Call['Timeout'],
                CURLOPT_HTTPHEADER => $Headers,
                CURLOPT_USERPWD => $UPWD, // FIXME
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $Call['Data']));

        return curl_exec($Call['Link']);
    });

    setFn ('Close', function ($Call)
    {
        curl_close ($Call['Link']);
        return true;
    });

    setFn('Execute', function ($Call)
    {
        return true;
    });

    setFn('Version', function ($Call)
    {
        $Call['Link'] = curl_init($Call['Where']['ID']);

        curl_setopt_array($Call['Link'],
                array(
                    CURLOPT_HEADER => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_COOKIEJAR => $Call['CookieFile'],
                    CURLOPT_FILETIME => true,
                    CURLOPT_NOBODY => true,
                    CURLOPT_FOLLOWLOCATION => $Call['Follow'],
                    CURLOPT_CONNECTTIMEOUT => $Call['Timeout']));

        return curl_getinfo($Call['Link'])['filetime'];
    });