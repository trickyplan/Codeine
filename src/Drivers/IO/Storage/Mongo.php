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

        F::Log('Connected to *'.$Call['Server'].'*', LOG_INFO, 'Administrator');

        $Link = $Link->selectDB($Call['Database']);

        F::Log('Database *'.$Call['Database'].'* selected', LOG_INFO, 'Administrator');

        if (isset($Call['Auth']))
        {
            if ($Link->authenticate($Call['Auth']['Username'], $Call['Auth']['Password']))
                F::Log('Authenticated as '.$Call['Auth']['Username'], LOG_INFO, 'Administrator');
            else
                F::Log('Authentication as '.$Call['Auth']['Username'].' failed', LOG_ERR, 'Administrator');
        }

        return $Link;
    });

    setFn('Where', function ($Call)
    {
        $Where = [];

        foreach ($Call['Where'] as $Key => $Value)
            if (is_array($Value))
            {
                foreach ($Value as $Subkey => $Subvalue)
                    if (is_numeric($Subkey))
                        $Where[$Key] = $Subvalue;
                    elseif (is_scalar($Subvalue) && substr($Subvalue, 0, 1) == '~')
                        $Where[$Key.'.'.$Subkey] = new MongoRegex(substr($Subvalue, 1));
                    elseif (substr($Subkey, 0, 1) == '$')
                        $Where[$Key][$Subkey] = $Subvalue;
                    else
                        $Where[$Key.'.'.$Subkey] = $Subvalue;
            }
            else
            {
                if (substr($Value, 0, 1) == '~')
                    $Where[$Key] = new MongoRegex(substr($Value, 1));
                else
                    $Where[$Key] = $Value;
            }

        $Call['Where'] = $Where;
        return $Call;
    });

    setFn ('Read', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');
        $Data = null;

        if (isset($Call['Where']) and $Call['Where'] !== null)
        {
            $Call = F::Apply(null, 'Where', $Call);

            if (isset($Call['Distinct']) && $Call['Distinct'])
            {
                F::Log('db.*'.$Call['Scope'].'*.distinct()', LOG_INFO, 'Administrator');
                $Data = $Call['Link']->$Call['Scope']->distinct($Call['Fields'][0], $Call['Where']);

                foreach ($Data as &$Value)
                    $Value = [$Call['Fields'][0] => $Value];

                if (isset($Call['Limit']))
                    $Data = array_slice($Data, $Call['Limit']['From'], $Call['Limit']['To']);
            }
            else
            {
                F::Log('db.*'.$Call['Scope'].'*.find('
                    .json_encode($Call['Where'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).')', LOG_INFO, 'Administrator');

                $Cursor = $Call['Link']->$Call['Scope']->find($Call['Where'],['_id' => 0]);
            }
        }
        else
        {
            if (isset($Call['Distinct']) && $Call['Distinct'])
            {
                F::Log('db.*'.$Call['Scope'].'*.distinct()', LOG_INFO, 'Administrator');
                $Data = $Call['Link']->$Call['Scope']->distinct($Call['Fields'][0]);

                foreach ($Data as $Key => &$Value)
                    $Value = [$Call['Fields'][0] => $Value];

                if (isset($Call['Limit']))
                    $Data = array_slice($Data, $Call['Limit']['From'], $Call['Limit']['To']);
            }
            else
            {
                F::Log('db.*'.$Call['Scope'].'*.find()', LOG_INFO, 'Administrator');
                $Cursor = $Call['Link']->$Call['Scope']->find([],['_id' => 0]);
            }
        }

        if (isset($Cursor))
        {
            if ($Cursor === null)
                ;
            else
            {
                if (isset($Call['Fields']))
                {
                    F::Log('*'.implode(',', $Call['Fields']).'* fields selected', LOG_INFO, 'Administrator');
                    $Fields = ['_id' => 0];

                    foreach ($Call['Fields'] as $Field)
                        $Fields[$Field] = true;

                    $Cursor->fields($Fields);
                }

                if (isset($Call['Sort']))
                    foreach($Call['Sort'] as $Key => $Direction)
                    {
                        $Direction = (int)(($Direction == SORT_ASC) or ($Direction == 1))? MongoCollection::ASCENDING: MongoCollection::DESCENDING;

                        $Cursor->sort([$Key => $Direction]);

                        if ($Direction == 1)
                            F::Log('Sorted by *'.$Key.'* ascending', LOG_INFO, 'Administrator');
                        else
                            F::Log('Sorted by *'.$Key.'* descending', LOG_INFO, 'Administrator');
                    }

                if (isset($Call['Limit']))
                {
                    $Cursor->limit($Call['Limit']['To'])->skip($Call['Limit']['From']);
                    F::Log('Sliced from *'.$Call['Limit']['From'].'* to '.$Call['Limit']['To'], LOG_INFO, 'Administrator');
                }

                if ($Cursor->count()>0)
                    $Data = iterator_to_array($Cursor, false);
            }
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
                if (isset($Call['Data'])) // Update Where
                {
                    $Request = 'db.*'.$Call['Scope'].'*.update('.j($Call['Where']).','.j(['$set' => $Call['Data']]).')';
                    $Result = $Call['Link']->$Call['Scope']->update(
                        $Call['Where'],
                        ['$set' => $Call['Data']],
                        [
                            'upsert' => $Call['Mongo']['Upsert Enabled'],
                            'multiple' => $Call['Mongo']['Multiple Updates'],
                            'w'         => $Call['Mongo']['Write Concerns']
                        ]);

                    if ($Result)
                        F::Log($Request, LOG_INFO, 'Administrator');
                    else
                        F::Log($Request.j($Result), LOG_ERR, 'Administrator');

                }
                else // Delete Where
                {
                    $Request = 'db.*'.$Call['Scope'].'*.remove('.j($Call['Where']).')';
                    $Result = $Call['Link']->$Call['Scope']->remove ($Call['Where'],
                    [
                        'justOne'  => $Call['Mongo']['Just One'],
                        'w'        => $Call['Mongo']['Write Concerns']
                    ]);

                    if ($Result)
                        F::Log($Request, LOG_INFO, 'Administrator');
                    else
                        F::Log($Request.j($Result), LOG_ERR, 'Administrator');
                }
            }
            else
            {
                if (isset($Call['Data'])) // Insert
                {
                    $Request = 'db.*'.$Call['Scope'].'*.insert('.j($Call['Data']).')';

                    $Result = $Call['Link']->$Call['Scope']->insert ($Call['Data'],
                    [
                        'w'        => $Call['Mongo']['Write Concerns']
                    ]);

                    if ($Result)
                        F::Log($Request, LOG_INFO, 'Administrator');
                    else
                        F::Log($Request.j($Result), LOG_ERR, 'Administrator');

                    unset($Call['Data']['_id']); // Mongo, are you kiddin'me?
                }
                else // Delete All
                {
                    $Request = 'db.*'.$Call['Scope'].'*.remove()';
                    $Result = $Call['Link']->$Call['Scope']->remove([],
                    [
                        'justOne'  =>  $Call['Mongo']['Just One'],
                        'w'        => $Call['Mongo']['Write Concerns']
                    ]);

                    if ($Result)
                        F::Log($Request, LOG_INFO, 'Administrator');
                    else
                        F::Log($Request.j($Result), LOG_ERR, 'Administrator');
                }
            }
        }
        catch (MongoException $e)
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
            $Call = F::Apply(null, 'Where', $Call);

            if (isset($Call['Distinct']) && $Call['Distinct'])
            {
                F::Log('db.*'.$Call['Scope'].'*.distinct('.j($Call['Where']).')', LOG_INFO, 'Administrator');
                $Data = $Call['Link']->$Call['Scope']->distinct($Call['Fields'][0], $Call['Where']);

                return count($Data);
            }
            else
            {
                F::Log('db.*'.$Call['Scope'].'*.find('.j($Call['Where']).').count()', LOG_INFO, 'Administrator');
                $Cursor = $Call['Link']->$Call['Scope']->find($Call['Where']);
            }
        }
        else
        {
            if (isset($Call['Distinct']) && $Call['Distinct'])
            {
                F::Log('db.*'.$Call['Scope'].'*.distinct()', LOG_INFO, 'Administrator');
                $Data = $Call['Link']->$Call['Scope']->distinct($Call['Fields'][0]);

                return count($Data);
            }
            else
            {
                F::Log('db.*'.$Call['Scope'].'*.find().count()', LOG_INFO, 'Administrator');
                $Cursor = $Call['Link']->$Call['Scope']->find();
            }
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
            $Cursor = $Call['Link']->$Call['Scope']->find($Call['Where'])->sort(['ID' => -1]);
        else
            $Cursor = $Call['Link']->$Call['Scope']->find()->sort(['ID' => -1]);

        $Cursor->limit(1);

        $IDs = iterator_to_array($Cursor);

        $ID = array_shift($IDs);

        if (!isset($ID['ID']))
            $ID['ID'] = 0;

        return ((int) $ID['ID'])+1;
    });

    setFn('Size', function ($Call)
    {
        return ($Call['Link']->execute('db.stats(1024)')['retval']['dataSize']).'K';
    });