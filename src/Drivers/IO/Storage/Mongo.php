<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Open', function ($Call)
    {
        $Link = new Mongo();
        $Link = $Link->selectDB($Call['Database']);

        if (isset($Call['Auth']))
            $Link->authentificate($Call['Auth']['Username'], $Call['Auth']['Password']);

        return $Link;
    });

    self::setFn ('Read', function ($Call)
    {
        if (isset($Call['Where']))
            $Cursor = $Call['Link']->$Call['Scope']->find($Call['Where']);
        else
            $Cursor = $Call['Link']->$Call['Scope']->find();

        if ($Cursor->count()>0)
            foreach ($Cursor as $cCursor)
                $Data[] = $cCursor;
        else
            $Data = null;

        return $Data;
    });

    self::setFn ('Write', function ($Call)
    {
        if (null === $Call['Data'])
            return $Call['Link']->$Call['Scope']->remove ($Call['Where']);
        else
        {
            if (isset($Call['Where']))
                return $Call['Link']->$Call['Scope']->update($Call['Where'], $Call['Data']);
            else
                return $Call['Link']->$Call['Scope']->insert ($Call['Data']);
        }
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {
        return $Call['Link']->execute($Call['Command']);
    });