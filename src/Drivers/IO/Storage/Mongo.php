<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Open', function ($Call)
    {
        $Link = new Mongo('mongodb://'.$Call['Server']);
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
            {
                unset($cCursor['_id']);
                $Data[] = $cCursor;
            }
        else
            $Data = null;

        return $Data;
    });

    self::setFn ('Write', function ($Call)
    {
        if (null === $Call['Data'])
        {
            if (isset($Call['Where']))
                return $Call['Link']->$Call['Scope']->remove ($Call['Where']);
            else
                return $Call['Link']->$Call['Scope']->remove ();
        }
        else
        {
            $Data = array();
            foreach ($Call['Data'] as $Key => $Value)
                $Data = F::Dot($Data, $Key, $Value);

            if (isset($Call['Where']))
                $Call['Link']->$Call['Scope']->update($Call['Where'], array('$set' => $Data));
            else
                $Call['Link']->$Call['Scope']->insert ($Data);

            return $Data;
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