<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Open', function ($Call)
    {
        // TODO MongoDB Authentification
        $Link = new Mongo();
        $Link = $Link->selectDB($Call['Database']);

        return $Link;
    });

    self::Fn ('Load', function ($Call)
    {
        return $Call['Link']->$Call['Scope']->findOne($Call['Where']);
    });

    self::Fn ('Create', function ($Call)
    {
        return $Call['Link']->$Call['Scope']->insert($Call['Data']);
    });

    self::Fn ('Delete', function ($Call)
    {
        return $Call['Link']->$Call['Scope']->remove($Call['Where']);
    });