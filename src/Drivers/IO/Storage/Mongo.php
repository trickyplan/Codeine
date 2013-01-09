<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Open', function ($Call)
    {
        $Link = new MongoClient('mongodb://'.$Call['Server']);
        $Link = $Link->selectDB($Call['Database']);

        if (isset($Call['Auth']))
            $Link->authenticate($Call['Auth']['Username'], $Call['Auth']['Password']);

        return $Link;
    });

    setFn ('Read', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');
        $Data = null;

        if (isset($Call['Where']))
            $Cursor = $Call['Link']->$Call['Scope']->find($Call['Where']);
        else
            $Cursor = $Call['Link']->$Call['Scope']->find();

        if (isset($Call['Fields']))
        {
            $Fields = array();
            foreach ($Call['Fields'] as $Field)
                $Fields[$Field] = true;

            $Cursor->fields($Fields);
        }

        if (isset($Call['Sort']))
            foreach($Call['Sort'] as $Key => $Direction)
                $Cursor->sort(array($Key => (int)(($Direction == SORT_ASC) or ($Direction == 1))? 1: -1));

        if (isset($Call['Limit']))
            $Cursor->limit($Call['Limit']['To']-$Call['Limit']['From'])->skip($Call['Limit']['From']);

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

    setFn ('Write', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');

        if (null === $Call['Data'])
        {
            if (isset($Call['Where']))
                return $Call['Link']->$Call['Scope']->remove ($Call['Where']);
            else
                return $Call['Link']->$Call['Scope']->remove ();
        }
        else
        {
            $Data = [];

            foreach ($Call['Data'] as $Key => $Value)
            {
                unset ($Call['Data'][$Key]);
                $Data = F::Dot($Data, $Key, $Value);
            }

            $Data = F::Merge($Call['Current'], $Data);

            try
            {
                if (isset($Call['Where']))
                    $Call['Link']->$Call['Scope']->update($Call['Where'], $Data);
                else
                    $Call['Link']->$Call['Scope']->insert ($Data);
            }
            catch (MongoCursorException $e)
            {
                return F::Hook('IO.Mongo.Update.Failed', $Call);
            }

            return $Data;
        }
    });

    setFn ('Close', function ($Call)
    {
        return true;
    });

    setFn ('Execute', function ($Call)
    {

        return $Call['Link']->execute($Call['Command']);
    });

    setFn ('Count', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');

        if (isset($Call['Where']))
            $Cursor = $Call['Link']->$Call['Scope']->find($Call['Where']);
        else
            $Cursor = $Call['Link']->$Call['Scope']->find();

        return $Cursor->count();
    });
