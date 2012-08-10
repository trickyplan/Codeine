<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */
    include "Mail.php";
    include('Mail/mime.php');

    self::setFn ('Open', function ($Call)
    {
        return Mail::factory('smtp',
                   array ('host' => $Call['Host'],
                     'auth' => true,
                     'username' => $Call['Username'],
                     'password' => $Call['Password']));
    });

    self::setFn('Write', function ($Call)
    {
        $Call['Headers'] = array ('From' => $Call['From'],'To' => $Call['Scope'].' <'.$Call['Scope'].'>', 'Subject' => $Call['ID']);

        $mime = new Mail_mime("\n");

        // Setting the body of the email
        $mime->setParam('html_charset', 'utf-8');
        $mime->setParam('text_charset', 'utf-8');
        $mime->setParam('head_charset', 'utf-8');

        $mime->setTXTBody(strip_tags($Call['Data']));
        $mime->setHTMLBody($Call['Data']);

        $Call['Data'] = $mime->get();
        $Call['Headers'] = $mime->headers($Call['Headers']);

        return $Call['Link']->send($Call['Scope'], $Call['Headers'], $Call['Data']);
    });
