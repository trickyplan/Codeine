<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */
    include_once 'Mail.php';
    include_once 'Mail/mime.php';

    setFn ('Open', function ($Call)
    {
        $Mail = new Mail;
        return $Mail->factory('smtp',
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

        if (isset($Call['From']))
            $Screen = $Call['From'];
        elseif ( isset($Call['Project']['Title']))
            $Screen = $Call['Project']['Title'].' <'.$Call['Username'].'>';
        else
            $Screen = 'Codeine <'.$Call['Username'].'>';

        $Call['Headers'] =  [
            'From' => $Screen,
            'To' => $Call['Scope'],
            'Subject' => $Call['ID']];

        $mime = new Mail_mime("\n");

        // Setting the body of the email
        $mime->setParam('html_charset', 'utf-8');
        $mime->setParam('text_charset', 'utf-8');
        $mime->setParam('head_charset', 'utf-8');

        if (is_array($Call['Data']))
        {
            foreach ($Call['Data'] as &$Row)
                $Row = implode("\t", $Row);

            $Call['Data'] = implode(PHP_EOL, $Call['Data']);
        }

        $mime->setTXTBody(strip_tags($Call['Data']));

        if (isset($Call['HTML Mail']) && $Call['HTML Mail'])
            $mime->setHTMLBody($Call['Data']);

        $Call['Data'] = $mime->get(['text_charset' => 'utf-8']);
        $Call['Headers'] = $mime->headers($Call['Headers']);

        return $Call['Link']->send($Call['Scope'], $Call['Headers'], $Call['Data']);
    });
