<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */
    include "Mail.php";

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
        $Call['Headers'] = array ('From' => $Call['From'], 'To' => $Call['To'], 'Subject' => $Call['ID']);
        return $Call['Link']->send($Call['Scope'], $Call['Headers'], $Call['Data']);
    });
