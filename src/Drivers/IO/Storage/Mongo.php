<?php

   /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Open', function ($Call)
    {
        $Link = null;

        $Server = F::Dot($Call, 'Mongo.Server'); // BC Parameters

        if ($Server === null)
        {
            $Server = F::Dot($Call, 'Server');

            if ($Server !== null)
                F::Log('"Server" key is deprecated. Use "Mongo.Server" instead', LOG_WARNING);
        }

        $Database = F::Dot($Call, 'Mongo.Database'); // BC Parameters
        if ($Database === null)
        {
            $Database = F::Dot($Call, 'Database');

            if ($Database !== null)
                F::Log('"Database" key is deprecated. Use "Mongo.Database" instead', LOG_WARNING);
        }

        $Server     = F::Variable($Server, $Call);
        $Database   = F::Variable($Database, $Call);

        if ($Server !== null)
        {
            if ($Database !== null)
            {
                $Call = F::Dot($Call, 'Mongo.Connect', F::Variable(F::Dot($Call, 'Mongo.Connect'), $Call));

                $Client = new MongoDB\Client('mongodb://'.$Server.'/'.$Database, F::Dot($Call, 'Mongo.Connect'));
                F::Log('Connected to *'.$Server.'* with options: '.j(F::Dot($Call, 'Mongo.Connect')), LOG_INFO, ['Administrator', 'Mongo']);
                
                $Link = $Client->selectDatabase($Database);
                F::Log('Database *'.$Database.'* selected', LOG_INFO, ['Administrator', 'Mongo']);
            }
            else
                F::Log('Database is not specified', LOG_ALERT, ['Administrator', 'Mongo']);
        }
        else
            F::Log('Server is not specified', LOG_ALERT, ['Administrator', 'Mongo']);

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
                        $Where[$Key.'.'.$Subkey] = new MongoDB\BSON\Regex(substr($Subvalue, 1));
                    elseif (substr($Subkey, 0, 1) == '$')
                    {
                        // FIXME Temporarily fixed
                        if ($Subkey == '$in')
                            sort($Subvalue);
                        $Where[$Key][$Subkey] = $Subvalue;
                    }
                    else
                        $Where[$Key.'.'.$Subkey] = $Subvalue;
            }
            else
            {
                if (substr($Value, 0, 1) == '~')
                    $Where[$Key] = new MongoDB\BSON\Regex(substr($Value, 1));
                else
                    $Where[$Key] = $Value;
            }

        $Call['Where'] = $Where;

        return $Call;
    });

    setFn('Options', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');
        $Call['Mongo']['Options'] = [];
        
        if (isset($Call['Fields']))
        {
            $Fields = ['_id' => 0];
            
            foreach ($Call['Fields'] as $Field)
                $Fields[$Field] = 1;
            
            $Call['Mongo']['Options']['projection'] = $Fields;
        }
        
        if (isset($Call['Sort']))
        {
            $Sort = [];
            foreach($Call['Sort'] as $Key => $Direction)
            {
                $Direction = (int)(($Direction == SORT_ASC) or ($Direction == 1))? +1: -1;
                
                if ($Direction == 1)
                    F::Log('Sorted by *'.$Key.'* ascending', LOG_INFO, ['Administrator', 'Mongo']);
                else
                    F::Log('Sorted by *'.$Key.'* descending', LOG_INFO, ['Administrator', 'Mongo']);
                
                $Sort[$Key] = $Direction;
            }
            
            $Call['Mongo']['Options']['sort'] = $Sort;
        }

        if (isset($Call['Limit']))
        {
            $Call['Mongo']['Options']['limit'] = (int) $Call['Limit']['To'];
            $Call['Mongo']['Options']['skip'] = (int) $Call['Limit']['From'];
            F::Log('Sliced *'.$Call['Limit']['To'].'* from *'.$Call['Limit']['From'].'*', LOG_INFO, ['Administrator', 'Mongo']);
        }
        
        if (F::Dot($Call, 'IO One') or F::Dot($Call, 'One'))
        {
            $Call['Mongo']['Options']['limit'] = 1;
            $Call['Mongo']['Options']['skip'] = 0;
            F::Log('Sliced by «One» option', LOG_INFO, ['Administrator', 'Mongo']);
        }
        
        return $Call;
    });
    
    setFn ('Read', function ($Call)
    {
        $Data = null;
        $Call = F::Apply(null, 'Options', $Call);
        
        if (isset($Call['Where']) and $Call['Where'] !== null)
        {
            $Call = F::Apply(null, 'Where', $Call);

            F::Log('db.*'.$Call['Scope'].'*.find('
                .j($Call['Where']).')', LOG_INFO, ['Administrator', 'Mongo']);
            F::Log('Options: ('.j($Call['Mongo']['Options']), LOG_INFO, ['Administrator', 'Mongo']);

            $Cursor = $Call['Link']->selectCollection($Call['Scope'])->find($Call['Where'], $Call['Mongo']['Options']);
        }
        else
        {
            F::Log('db.*'.$Call['Scope'].'*.find()', LOG_INFO, ['Administrator', 'Mongo']);
            F::Log('Options: ('.j($Call['Mongo']['Options']), LOG_INFO, ['Administrator', 'Mongo']);
            $Cursor = $Call['Link']->selectCollection($Call['Scope'])->find([],$Call['Mongo']['Options']);
        }

        if (isset($Cursor))
        {
            if ($Cursor === null)
                ;
            else
            {
                F::Run(null, 'Explain', $Call);
                /*if (isset($Call['Mongo']['Read']['maxTimeMS']))
                    $Cursor->maxTimeMS($Call['Mongo']['Read']['maxTimeMS']);*/
                $Cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
                $Data = $Cursor->toArray();

                foreach ($Data as $IX => $Object)
                    unset($Data[$IX]['_id']);

                F::Log(count($Data).' documents loaded from *'.$Call['Scope'].'*', LOG_INFO,['Administrator', 'Mongo']);
            }
        }

        return $Data;
    });

    setFn ('Write', function ($Call)
    {
        $Call = F::Apply(null, 'Options', $Call);
        
        unset($Call['Data']['_id']);
        try
        {
            if (isset($Call['Where']))
            {
                if (isset($Call['Data'])) // Update Where
                {
                    $Request = 'db.*'.$Call['Scope'].'*.update('.j($Call['Where']).','.j($Call['Data']).')';
                    $Result = $Call['Link']->selectCollection($Call['Scope'])->replaceOne(
                        $Call['Where'],
                        $Call['Data'], $Call['Mongo']['Options']);

                    $ModifiedCount = $Result->getModifiedCount();
                    $Level = ($ModifiedCount === 1 ? LOG_INFO: LOG_ERR);
                    F::Log($ModifiedCount.' objects updated. ', $Level, ['Administrator', 'Mongo']);

                    F::Log($Request, $Level, ['Administrator', 'Mongo']);
                }
                else // Delete Where
                {
                    $Request = 'db.*'.$Call['Scope'].'*.remove('.j($Call['Where']).')';
                    $Result = $Call['Link']->selectCollection($Call['Scope'])->deleteMany($Call['Where'], $Call['Mongo']['Options']);

                    $DeletedCount = $Result->getDeletedCount();
                    $Level = ($DeletedCount === 1 ? LOG_INFO: LOG_ERR);
                    F::Log($DeletedCount.' objects deleted. ', $Level, ['Administrator', 'Mongo']);
                    F::Log($Request, $Level, ['Administrator', 'Mongo']);
                }
            }
            else
            {
                if (isset($Call['Data'])) // Insert
                {
                    $Request = 'db.*'.$Call['Scope'].'*.insert('.j($Call['Data']).')';

                    $Result = $Call['Link']->selectCollection($Call['Scope'])->insertOne($Call['Data'], $Call['Mongo']['Options']);

                    $InsertedCount = $Result->getInsertedCount();
                    $Level = ($InsertedCount === 1 ? LOG_INFO: LOG_ERR);
                    F::Log($InsertedCount.' objects inserted. ', $Level, ['Administrator', 'Mongo']);
                    F::Log($Request, $Level, ['Administrator', 'Mongo']);
                }
                else // Delete All
                {
                    $Request = 'db.*'.$Call['Scope'].'*.remove()';
                    $Result = $Call['Link']->selectCollection($Call['Scope'])->deleteMany([], $Call['Mongo']['Options']);

                    $DeletedCount = $Result->getDeletedCount();
                    $Level = ($DeletedCount === 1 ? LOG_INFO: LOG_ERR);
                    F::Log($DeletedCount.' objects deleted. ', $Level, ['Administrator', 'Mongo']);

                    F::Log($Request, $Level, ['Administrator', 'Mongo']);
                    F::Log('Options: ('.j($Call['Mongo']['Options']), LOG_INFO, ['Administrator', 'Mongo']);
                }
            }

            F::Log('Options: '.j($Call['Mongo']['Options']).'', LOG_INFO, ['Administrator', 'Mongo']);
        }
        catch (MongoException $e)
        {
            F::Log('Mongo Exception: '.$e->getMessage(), LOG_ERR, ['Administrator', 'Mongo']);
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
        F::Log($Call['Command'], LOG_INFO);
        $Cursor = $Call['Link']->command($Call['Command']);
        $Cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
        return $Cursor->toArray();
    });

    setFn ('Count', function ($Call)
    {
        unset($Call['Internal One'], $Call['One'], $Call['IO One']);

        $Call = F::Apply(null, 'Options', $Call);

        if (isset($Call['Where']) and $Call['Where'] !== null)
        {
            $Call = F::Apply(null, 'Where', $Call);

            F::Run(null, 'Explain', $Call);
            
            if (isset($Call['Distinct']) && $Call['Distinct'])
            {
                F::Log('db.*'.$Call['Scope'].'*.distinct('.j($Call['Where']).')', LOG_INFO, ['Administrator', 'Mongo']);
                $Data = $Call['Link']->selectCollection($Call['Scope'])->distinct($Call['Fields'][0], $Call['Where'], $Call['Mongo']['Options']);
                
                return count($Data);
            }
            else
            {
                F::Log('db.*'.$Call['Scope'].'*.count('.j($Call['Where']).')', LOG_INFO, ['Administrator', 'Mongo']);
                $Cursor = $Call['Link']->selectCollection($Call['Scope'])->count($Call['Where'], $Call['Mongo']['Options']);
            }
        }
        else
        {
            if (isset($Call['Distinct']) && $Call['Distinct'])
            {
                F::Log('db.*'.$Call['Scope'].'*.distinct()', LOG_INFO, ['Administrator', 'Mongo']);
                $Data = $Call['Link']->selectCollection($Call['Scope'])->distinct($Call['Fields'][0]);

                $Cursor = $Call['Link']->selectCollection($Call['Scope'])->count();
            }
            else
            {
                F::Log('db.*'.$Call['Scope'].'*.count()', LOG_INFO, ['Administrator', 'Mongo']);
                $Cursor = $Call['Link']->selectCollection($Call['Scope'])->count();
            }
        }

        if ($Cursor)
            return $Cursor;
        else
            return null;
    });

    setFn ('ID', function ($Call)
    {
        $Call = F::Apply(null, 'Options', $Call);

        $Counter = F::Run(null, 'Read', $Call, ['Scope' => 'Counters', 'Where' => ['ID' => $Call['Entity']]]);

        if (empty($Counter))
        {
            if (isset($Call['Where']))
                $Cursor = $Call['Link']->selectCollection($Call['Scope'])->find($Call['Where'],
                    ['sort' => ['ID' => -1]]);
            else
                $Cursor = $Call['Link']->selectCollection($Call['Scope'])->find([],
                    ['sort' => ['ID' => -1]]);

            $Cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
            $Data = $Cursor->toArray();

            $Last = array_shift($Data);

            if (isset($Last['ID']))
                ;
            else
                $Last['ID'] = 0;

            $Last = ((int) $Last['ID'])+1;
        }
        else
            $Last = $Counter[0]['Value']+1;

        F::Run(null, 'Write', $Call,
            [
                'Scope' => 'Counters',
                'Where' => ['ID' => $Call['Entity']],
                'Data!' => ['ID' => $Call['Entity'], 'Value' => $Last],
                'Mongo' =>
                [
                    'Update' =>
                    [
                        'upsert' => true
                    ]
                ]
            ]);

        return $Last;
    });

    setFn('Size', function ($Call)
    {
        $Cursor = $Call['Link']->command(['dbStats' => 1024]);
        $Cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
        return ($Cursor->toArray()[0]['dataSize']).'K';
    });

    setFn('Distinct', function ($Call)
    {
        $Call = F::Apply(null, 'Options', $Call);
        
        $Data = [];
        
        if (isset($Call['Where']))
            ;
        else
            $Call['Where'] = [];
            
        foreach ($Call['Fields'] as $Field)
        {
            $Command = 'db.'.$Call['Scope'].'.distinct("'.$Field.'", '.j($Call['Where']).')';
            F::Log($Command, LOG_INFO, ['Administrator', 'Mongo']);
            $Data[$Field] = $Call['Link']->selectCollection($Call['Scope'])->distinct($Field, $Call['Where']);
            
            $Values = [];
            
            foreach ($Data[$Field] as $Value)
                if (is_scalar($Value))
                    $Values[$Value] = $Value;
            
            sort($Values);
            $Data[$Field] = $Values;
        }
        
        return $Data;
    });
    
    setFn('Explain', function ($Call)
    {
        if (F::Environment() == 'Development')
        {
            if (isset($Call['Where']))
            {
                F::Log(function () use ($Call)
                {
                    $Explain = $Call['Link']->command(
                        [
                            'explain'   =>
                                [
                                    'find'  => $Call['Scope'],
                                    'filter' => $Call['Where']
                                ],
                            'verbosity' => 'queryPlanner'
                        ]
                    );
                    $Explain = $Explain->toArray();
                    return 'Mongo explained: '.j($Explain);
                }, LOG_DEBUG, ['Administrator', 'Mongo']);
            }
        }
        return $Call;
    });