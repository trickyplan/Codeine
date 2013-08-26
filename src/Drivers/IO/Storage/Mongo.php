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

        F::Log('Connected to *'.$Call['Server'].'*');

        $Link = $Link->selectDB($Call['Database']);

        F::Log('Database *'.$Call['Database'].'* selected');

        if (isset($Call['Auth']))
        {
            if ($Link->authenticate($Call['Auth']['Username'], $Call['Auth']['Password']))
                F::Log('Authenticated as '.$Call['Auth']['Username']);
            else
                F::Log('Authentication as '.$Call['Auth']['Username'].' failed');
        }

        return $Link;
    });

    setFn ('Read', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');
        $Data = null;

        if (isset($Call['Where']) and $Call['Where'] !== null)
        {
            $Where = [];

            foreach ($Call['Where'] as $Key => $Value)
                if (is_array($Value))
                    foreach ($Value as $Subkey => $Subvalue)
                        if (is_numeric($Subkey))
                            $Where[$Key] = $Subvalue;
                        elseif (substr($Subkey, 0, 1) == '$')
                            $Where[$Key][$Subkey] = $Subvalue;
                        else
                            $Where[$Key.'.'.$Subkey] = $Subvalue;
                else
                    $Where[$Key] = $Value;

            F::Log('db.*'.$Call['Scope'].'*.find('
                .json_encode($Where, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).')', LOG_INFO);
            $Cursor = $Call['Link']->$Call['Scope']->find($Where,['_id' => 0]);
        }
        else
        {
            F::Log('db.*'.$Call['Scope'].'*.find()', LOG_INFO);
            $Cursor = $Call['Link']->$Call['Scope']->find([],['_id' => 0]);
        }

        $Data = null;

        if ($Cursor !== null)
        {
            if (isset($Call['Fields']))
            {
                $Fields = [];

                foreach ($Call['Fields'] as $Field)
                    $Fields[$Field] = true;

                $Cursor->fields($Fields);
            }

            if (isset($Call['Sort']))
                foreach($Call['Sort'] as $Key => $Direction)
                    $Cursor->sort(array($Key => (int)(($Direction == SORT_ASC) or ($Direction == 1))? 1: -1));

            if (isset($Call['Limit']))
                $Cursor->limit($Call['Limit']['To'])->skip($Call['Limit']['From']);

            if ($Cursor->count()>0)
                $Data = iterator_to_array($Cursor, false);
        }

        return $Data;
    });

    setFn ('Write', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');

        try
        {
            if (isset($Call['Where']))
            {
                if (isset($Call['Data']))
                {
                    F::Log('db.*'.$Call['Scope'].'*.update('
                        .json_encode($Call['Where']).','
                        .json_encode(['$set' => $Call['Data']], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).')',LOG_INFO);

                    $Call['Link']->$Call['Scope']->update($Call['Where'], ['$set' => $Call['Data']], ['upsert' => true, 'multiple' => true]);

                }
                else
                {
                    F::Log('db.*'.$Call['Scope'].'*remove('
                    .json_encode($Call['Where'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).')', LOG_INFO);

                    $Call['Link']->$Call['Scope']->remove ($Call['Where'], ['multiple' => true]);
                }
            }
            else
            {
                if (isset($Call['Data']))
                {
                    F::Log('db.*'.$Call['Scope'].'*.insert('
                    .json_encode($Call['Data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).')', LOG_INFO);

                    $Call['Link']->$Call['Scope']->insert ($Call['Data']);
                    unset($Call['Data']['_id']); // Mongo, are you kiddin'me?
                }
                else
                {
                    if (isset($Call['Where']))
                    {
                        F::Log('db.*'.$Call['Scope'].'*remove('
                        .json_encode($Call['Where'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).')', LOG_INFO);

                        $Call['Link']->$Call['Scope']->remove ($Call['Where'], ['multiple' => true]);
                    }
                    else
                    {
                        F::Log('db.*'.$Call['Scope'].'*remove()', LOG_INFO);
                        $Call['Link']->$Call['Scope']->remove ();
                    }
                }
            }
        }
        catch (MongoCursorException $e)
        {
            return F::Hook('IO.Mongo.Write.Failed', $Call);
        }

        return isset($Call['Data'])? $Call['Data']: null;
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

        if (isset($Call['Where']) and $Call['Where'] !== null)
        {
            $Where = [];

            foreach ($Call['Where'] as $Key => $Value)
                if (is_array($Value))
                    foreach ($Value as $Subkey => $Subvalue)
                        if (is_numeric($Subkey))
                            $Where[$Key] = $Subvalue;
                        elseif (substr($Subkey, 0, 1) == '$')
                            $Where[$Key][$Subkey] = $Subvalue;
                        else
                            $Where[$Key.'.'.$Subkey] = $Subvalue;
                else
                    $Where[$Key] = $Value;

            F::Log('db.*'.$Call['Scope'].'*.find('.json_encode($Where, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).').count()', LOG_INFO);
            $Cursor = $Call['Link']->$Call['Scope']->find($Where);
        }
        else
        {
            F::Log('db.*'.$Call['Scope'].'*.find().count()', LOG_INFO);
            $Cursor = $Call['Link']->$Call['Scope']->find();
        }

        if ($Cursor)
            return $Cursor->count();
        else
            return null;
    });

    setFn ('ID', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');

        if (isset($Call['Where']))
            $Cursor = $Call['Link']->$Call['Scope']->find($Call['Where']);
        else
            $Cursor = $Call['Link']->$Call['Scope']->find();

        return $Cursor->count()+1;
    });

