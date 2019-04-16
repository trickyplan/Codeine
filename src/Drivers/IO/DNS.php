<?php

    setFn('Exists', function ($Call) {
        $Domain = F::Dot($Call, 'Where.ID');
        $Exists = checkdnsrr($Domain, $Call['Record Type']);
        F::Log($Domain.' '.$Call['Record Type'].'-record '.($Exists ? ' exists' : 'doesn\'t exist'), LOG_INFO);
        return $Exists;
    });

    setFn('Read', function ($Call) {
        $Domain = F::Dot($Call, 'Where.ID');
        $IP = gethostbyname($Domain);
        F::Log('Domain '.$Domain.' has been resolved to ip '.$IP, LOG_INFO);
        return $IP;
    });

    setFn('Write', function ($Call) {
        return null;
    });