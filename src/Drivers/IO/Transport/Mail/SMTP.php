<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    include_once 'Mail.php';
    include_once 'Mail/mime.php';

    setFn ('Open', function ($Call)
    {
        $Mail = new Mail;

        if (isset($Call['SMTP Auth']) && $Call['SMTP Auth'])
            return $Mail->factory('smtp',
                   [
                        'host' => $Call['Server'],
                        'port' => isset($Call['Port'])? $Call['Port']: 25,
                        'auth' => $Call['SMTP Auth'],
                        'username' => $Call['Username'],
                        'password' => $Call['Password']
                   ]);
        else
            return $Mail->factory('smtp',
                   [
                     'host' => $Call['Server'],
                     'port' => isset($Call['Port'])? $Call['Port']: 25
                   ]);
    });

    setFn('Write', function ($Call)
    {
        if (isset($Call['Link']))
            ;
        else
            $Call['Link'] = F::Run(null, 'Open', $Call);

        self::$_Perfect = false;

        if (isset($Call['From']))
            $Screen = $Call['From'];
        else
        {
            if (isset($Call['Project']['Title']))
                $Screen = $Call['Project']['Title'].' <'.$Call['Username'].'>';
            else
                $Screen = 'Codeine';
        }
        
        if (preg_match('/<(.+)>/', $Screen))
            ;
        else
            $Screen.= ' <'.$Call['Username'].'>';
        
        $Call['Headers']['From'] = $Screen;
        $Call['Headers']['To'] = $Call['Scope'];
        $Call['Headers']['Subject'] = $Call['Where']['ID'];
        
        /*if (F::Environment() != 'Production')
            $Call['Headers']['Subject'] .= ' ['.gethostname().']';*/ // FIXME Options
        
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

        F::Log('Sending mail "'.$Call['Where']['ID'].'" to '.$Call['Scope'].' with '.$Call['Server'], LOG_INFO, 'Administrator');

        $Result = $Call['Link']->send($Call['Scope'], $Call['Headers'], $Call['Data']);
       
        if ($Result instanceof PEAR_Error)
           F::Log($Result->getMessage(), LOG_ERR, 'Administrator'); // Temp.

        return $Call['Data'];
    });
