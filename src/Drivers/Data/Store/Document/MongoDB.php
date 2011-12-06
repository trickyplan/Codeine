<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Open', function ($Call)
    {
        // TODO MongoDB Authentification
        $Link = new Mongo();
        $Link = $Link->selectDB($Call['Database']);

        return $Link;
    });

    self::setFn ('Load', function ($Call)
    {
        return $Call['Link']->$Call['Scope']->findOne($Call['Where']);
    });

    self::setFn ('Create', function ($Call)
    {
        return $Call['Link']->$Call['Scope']->insert($Call['Data']);
    });

    self::setFn ('Delete', function ($Call)
    {
        return $Call['Link']->$Call['Scope']->remove($Call['Where']);
    });

    self::setFn ('Update', function ($Call)
    {
        return $Call['Link']->$Call['Scope']->save(
            F::Merge($Call['Link']->$Call['Scope']->findOne($Call['Where']),$Call['Data'])
        );
    });