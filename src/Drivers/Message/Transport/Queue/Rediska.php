<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn('Open', function ($Call)
    {
        $options = array(
           'namespace' => '_',
           'servers'   => array(
             array('host' => '127.0.0.1', 'port' => 6379)
           )
         );

        require_once 'Rediska.php';
        return new Rediska($options);
    });

    self::setFn('Send', function ($Call)
    {
        return $Call['Link']->publish($Call['Scope'], $Call['Message']);
    });

    self::setFn('Receive', function ($Call)
    {
        $Channel = $Call['Link']->subscribe($Call['Scope']);

        foreach ($Channel as $Message)
            return $Message;
    });