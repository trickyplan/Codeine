<?php

   /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Open', function ($Call)
    {
        $Client = new MongoDB\Client('mongodb://'.$Call['Server'].'/'.$Call['Database'], $Call['Mongo']['Connect']);
        F::Log('Connected to *'.$Call['Server'].'*', LOG_INFO, 'Administrator');
        
        $Link = $Client->selectDatabase($Call['Database']);
        F::Log('Database *'.$Call['Database'].'* selected', LOG_INFO, 'Administrator');

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
/*                    else
                        $Where[$Key.'.'.$Subkey] = $Subvalue;*/
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

    setFn('Options', function ($Call)
    {
        $Call['Mongo']['Options'] = [];
        
        if (isset($Call['Fields']))
        {
            F::Log('*'.implode(',', $Call['Fields']).'* fields selected', LOG_INFO, 'Administrator');

            foreach ($Call['Fields'] as $Field)
                $Fields[$Field] = 1;

            $Fields = ['_id' => 0];
            $Call['Mongo']['Options']['projection'] = $Fields;
        }
        
        if (isset($Call['Sort']))
        {
            $Sort = [];
            foreach($Call['Sort'] as $Key => $Direction)
            {
                $Direction = (int)(($Direction == SORT_ASC) or ($Direction == 1))? +1: -1;
                
                if ($Direction == 1)
                    F::Log('Sorted by *'.$Key.'* ascending', LOG_INFO, 'Administrator');
                else
                    F::Log('Sorted by *'.$Key.'* descending', LOG_INFO, 'Administrator');
                
                $Sort[$Key] = $Direction;
            }
            
            $Call['Mongo']['Options']['sort'] = $Sort;
        }

        if (isset($Call['Limit']))
        {
            $Call['Mongo']['Options']['limit'] = $Call['Limit']['To'];
            F::Log('Sliced *'.$Call['Limit']['To'].'* from *'.$Call['Limit']['From'].'*', LOG_INFO, 'Administrator');
        }
        
        return $Call;
    });
    
    setFn ('Read', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');
        $Data = null;
        $Call = F::Apply(null, 'Options', $Call);
        
        if (isset($Call['Where']) and $Call['Where'] !== null)
        {
            $Call = F::Apply(null, 'Where', $Call);

            if (isset($Call['Distinct']) && $Call['Distinct'])
            {
                F::Log('db.*'.$Call['Scope'].'*.distinct("'.$Call['Fields'][0].'")', LOG_INFO, 'Administrator');
                $Data = $Call['Link']->$Call['Scope']->distinct($Call['Fields'][0], $Call['Where']);

                foreach ($Data as &$Value)
                    $Value = [$Call['Fields'][0] => $Value];

                /*if (isset($Call['Limit']))
                    $Data = array_slice($Data, $Call['Limit']['From'], $Call['Limit']['To']);*/
            }
            else
            {
                F::Log('db.*'.$Call['Scope'].'*.find('
                    .j($Call['Where'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).')', LOG_INFO, 'Administrator');

                $Cursor = $Call['Link']->selectCollection($Call['Scope'])->find($Call['Where'], $Call['Mongo']['Options']);
            }
        }
        else
        {
            if (isset($Call['Distinct']) && $Call['Distinct'])
            {
                F::Log('db.*'.$Call['Scope'].'*.distinct("'.$Call['Fields'][0].'")', LOG_INFO, 'Administrator');
                $Data = $Call['Link']->$Call['Scope']->distinct($Call['Fields'][0]);

                foreach ($Data as $Key => &$Value)
                    $Value = [$Call['Fields'][0] => $Value];

                if (isset($Call['Limit']))
                    $Data = array_slice($Data, $Call['Limit']['From'], $Call['Limit']['To']);
            }
            else
            {
                F::Log('db.*'.$Call['Scope'].'*.find()', LOG_INFO, 'Administrator');

                $Cursor = $Call['Link']->selectCollection($Call['Scope'])->find([],$Call['Mongo']['Options']);
            }
        }

        if (isset($Cursor))
        {
            if ($Cursor === null)
                ;
            else
            {
                /*if (isset($Call['Mongo']['Read']['maxTimeMS']))
                    $Cursor->maxTimeMS($Call['Mongo']['Read']['maxTimeMS']);*/
                $Cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);

                if (count($Cursor) > 0)
                    $Data = $Cursor->toArray();
                
                foreach ($Data as $IX => $Object)
                    unset($Data[$IX]['_id']);
            }
        }

        return $Data;
    });

    setFn ('Write', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');
        $Call = F::Apply(null, 'Options', $Call);
        unset($Call['Data']['_id']);
        try
        {
            if (isset($Call['Where']))
            {
                if (isset($Call['Data'])) // Update Where
                {
                    $Request = 'db.*'.$Call['Scope'].'*.update('.j($Call['Where']).','.j($Call['Data']).')';
                    $Result = $Call['Link']->selectCollection($Call['Scope'])->updateMany(
                        $Call['Where'],
                        ['$set' => $Call['Data']], $Call['Mongo']['Options']);

                    if ($Result)
                        F::Log($Request, LOG_INFO, 'Administrator');
                    else
                        F::Log($Request.j($Result), LOG_ERR, 'Administrator');

                }
                else // Delete Where
                {
                    $Request = 'db.*'.$Call['Scope'].'*.remove('.j($Call['Where']).')';
                    $Result = $Call['Link']->selectCollection($Call['Scope'])->deleteMany($Call['Where'], $Call['Mongo']['Options']);

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

                    $Result = $Call['Link']->selectCollection($Call['Scope'])->insertOne($Call['Data'], $Call['Mongo']['Options']);

                    if ($Result)
                        F::Log($Request, LOG_INFO, 'Administrator');
                    else
                        F::Log($Request.j($Result), LOG_ERR, 'Administrator');
                }
                else // Delete All
                {
                    $Request = 'db.*'.$Call['Scope'].'*.remove()';
                    $Result = $Call['Link']->selectCollection($Call['Scope'])->deleteMany([], $Call['Mongo']['Options']);

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
        F::Log($Call['Command'], LOG_INFO);
        return $Call['Link']->execute($Call['Command']);
    });

    setFn ('Count', function ($Call)
    {
        $Call = F::Apply(null, 'Options', $Call);
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');

        if (isset($Call['Where']) and $Call['Where'] !== null)
        {
            $Call = F::Apply(null, 'Where', $Call);

            if (isset($Call['Distinct']) && $Call['Distinct'])
            {
                F::Log('db.*'.$Call['Scope'].'*.distinct('.j($Call['Where']).')', LOG_INFO, 'Administrator');
                $Data = $Call['Link']->selectCollection($Call['Scope'])->distinct($Call['Fields'][0], $Call['Where'], $Call['Mongo']['Options']);

                return count($Data);
            }
            else
            {
                F::Log('db.*'.$Call['Scope'].'*.count('.j($Call['Where']).')', LOG_INFO, 'Administrator');
                $Cursor = $Call['Link']->selectCollection($Call['Scope'])->count($Call['Where'], $Call['Mongo']['Options']);
            }
        }
        else
        {
            if (isset($Call['Distinct']) && $Call['Distinct'])
            {
                F::Log('db.*'.$Call['Scope'].'*.distinct()', LOG_INFO, 'Administrator');
                $Data = $Call['Link']->selectCollection($Call['Scope'])->distinct($Call['Fields'][0]);

                return count($Data);
            }
            else
            {
                F::Log('db.*'.$Call['Scope'].'*.count()', LOG_INFO, 'Administrator');
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
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');

        $Counter = F::Run(null, 'Read', $Call, ['Scope' => 'Counters', 'Where' => ['ID' => $Call['Entity']]]);

        if (empty($Counter))
        {
            if (isset($Call['Where']))
                $Cursor = $Call['Link']->$Call['Scope']->find($Call['Where'])->sort(['ID' => -1]);
            else
                $Cursor = $Call['Link']->$Call['Scope']->find()->sort(['ID' => -1]);

            $Cursor->limit(1);

            $IDs = iterator_to_array($Cursor);

            $ID = array_shift($IDs);

            if (!isset($ID['ID']))
                $ID['ID'] = 0;

            $ID = ((int) $ID['ID'])+1;
        }
        else
            $ID = $Counter[0]['Value']+1;

        F::Run(null, 'Write', $Call,
            [
                'Scope' => 'Counters',
                'Where' => ['ID' => $Call['Entity']],
                'Data!' => ['ID' => $Call['Entity'], 'Value' => $ID],
                'Mongo' =>
                [
                    'Update' =>
                    [
                        'upsert' => true
                    ]
                ]
            ]);

        return $ID;
    });

    setFn('Size', function ($Call)
    {
        return ($Call['Link']->execute('db.stats(1024)')['retval']['dataSize']).'K';
    });

    setFn('Create Index', function ($Call)
    {
        $Command = 'db.'.$Call['Entity'].'.ensureIndex({"'.$Call['Node'].'": 1})';
        F::Log($Command, LOG_INFO, 'Administrator');
        $Call['Link']->execute($Command);
        return $Call;
    });