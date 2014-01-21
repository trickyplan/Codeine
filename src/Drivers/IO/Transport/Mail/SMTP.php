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
                     'host' => $Call['Server'],
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

        $Call['Headers']['From'] = $Screen;
        $Call['Headers']['To'] = $Call['Scope'];
        $Call['Headers']['Subject'] = $Call['ID'];

        $mime = new Mail_mime();

        // Setting the body of the email
        $mime->setParam('html_charset', 'utf-8');
        $mime->setParam('text_charset', 'utf-8');
        $mime->setParam('head_charset', 'utf-8');

        if (is_array($Call['Data']))
            $Call['Data'] = implode(PHP_EOL, $Call['Data']);

        if (isset($Call['HTML Mail']) && $Call['HTML Mail'])
        {
            $mime->setHTMLBody($Call['Data']);
            $Call['Headers']['Content-Type'] = 'text/html; charset=utf-8';
        }
        else
            $mime->setTXTBody(strip_tags($Call['Data']));

        $Call['Data'] = $mime->get(['text_charset' => 'utf-8']);
        $Call['Headers'] = $mime->headers($Call['Headers']);

        $Result = $Call['Link']->send($Call['Scope'], $Call['Headers'], $Call['Data']);

        if ($Result instanceof PEAR_Error)
            F::Log($Result->getMessage(), LOG_INFO); // Temp.

        return $Call['Data'];
    });
