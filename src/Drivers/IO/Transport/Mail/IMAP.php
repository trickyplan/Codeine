<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Open', function ($Call)
    {
        return imap_open($Call['Server'], $Call['Username'], $Call['Password']);
    });

    setFn('Read', function ($Call)
    {
        $EMails = imap_search($Call['Link'], $Call['Where']['ID']);

        /* if emails are returned, cycle through each... */
        if ($EMails)
        {
            /* for every email... */
            $Data = [];

            $IX = 0;
            foreach($EMails as $Number)
            {
                /* get information specific to this email */
                $Data[$IX] = (array) imap_fetch_overview($Call['Link'], $Number, 0)[0];
                $Data[$IX]['message'] = base64_decode(imap_fetchbody($Call['Link'], $Number, 2));
                $IX++;
            }
        }
        else
            $Data = null;

        return $Data;
    });