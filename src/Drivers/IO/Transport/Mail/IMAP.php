<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
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

    setFn('Write', function ($Call)
    {
        $Headers['from'] = $Call['From'];
        $Headers['subject'] = $Call['ID'];
        $Headers['date']    = date(DATE_RFC2822);

        $HTML = [];
        $Plain = [];

        $HTML['type'] = TYPETEXT;
        $HTML['charset'] = 'utf-8';
        $HTML['subtype'] = 'html';
        $HTML['description'] = '';
        $HTML['contents.data'] = $Call['Data'];

        $Plain['type'] = TYPETEXT;
        $Plain['charset'] = 'utf-8';
        $Plain['subtype'] = 'plain';
        $Plain['description'] = '';
        $Plain['contents.data'] = strip_tags($Call['Data']);

        $Body =  [
                    ['type' => TYPEMULTIPART, 'subtype' => 'alternative'],
                    $HTML,
                    $Plain
                ];
        $Envelope = str_replace("\r",'',imap_mail_compose($Headers, $Body));

        imap_mail($Call['Scope'], $Call['ID'],$Envelope);

        return $Call['Data'];
    });