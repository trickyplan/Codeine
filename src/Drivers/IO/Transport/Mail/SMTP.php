<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */
    include 'Mail.php';
    include 'Mail/mime.php';

    setFn ('Open', function ($Call)
    {
        return Mail::factory('smtp',
                   array (
                     'host' => $Call['Host'],
                     'port' => isset($Call['Port'])? $Call['Port']: 25,
                     'auth' => true,
                     'username' => $Call['Username'],
                     'password' => $Call['Password'],
                   ));
    });

    setFn('Write', function ($Call)
    {
        $Call['Headers'] =  [
            'Return-path' => $Call['Username'],
            'From' => $Call['From'].' <'.$Call['Username'].'>',
            'To' => $Call['Scope'],
            'Subject' => $Call['ID']];

        $mime = new Mail_mime("\n");

        // Setting the body of the email
        $mime->setParam('html_charset', 'utf-8');
        $mime->setParam('text_charset', 'utf-8');
        $mime->setParam('head_charset', 'utf-8');

        $mime->setTXTBody(strip_tags($Call['Data']));
        $mime->setHTMLBody($Call['Data']);

        $Call['Data'] = $mime->get(array('text_charset' => 'utf-8'));
        $Call['Headers'] = $mime->headers($Call['Headers']);

        return $Call['Link']->send($Call['Scope'], $Call['Headers'], $Call['Data']);
    });
