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

    setFn('Options', function ($Call)
    {
        $Call['Scope'] = strtr($Call['Scope'], '.', '_');
        $Call['Mongo']['Options'] = [];
        
        if (isset($Call['Fields']))
        {
            F::Log('*'.implode(',', $Call['Fields']).'* fields selected', LOG_INFO, 'Administrator');

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
                    F::Log('Sorted by *'.$Key.'* ascending', LOG_INFO, 'Administrator');
                else
                    F::Log('Sorted by *'.$Key.'* descending', LOG_INFO, 'Administrator');
                
                $Sort[$Key] = $Direction;
            }
            
            $Call['Mongo']['Options']['sort'] = $Sort;
        }

        if (isset($Call['Limit']))
        {
            $Call['Mongo']['Options']['limit'] = (int) $Call['Limit']['To'];
            $Call['Mongo']['Options']['skip'] = (int) $Call['Limit']['From'];
            F::Log('Sliced *'.$Call['Limit']['To'].'* from *'.$Call['Limit']['From'].'*', LOG_INFO, 'Administrator');
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
                .j($Call['Where'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).')', LOG_INFO, 'Administrator');

            $Cursor = $Call['Link']->selectCollection($Call['Scope'])->find($Call['Where'], $Call['Mongo']['Options']);
        }
        else
        {
            F::Log('db.*'.$Call['Scope'].'*.find()', LOG_INFO, 'Administrator');
            $Cursor = $Call['Link']->selectCollection($Call['Scope'])->find([],$Call['Mongo']['Options']);
        }

        if (isset($Cursor))
        {
            if ($Cursor === null)
                ;
            else
            {
                if (F::Environment() == 'Development')
                {
                    if (isset($Call['Where']))
                    {
                        F::Log(function () use ($Call) {
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
                    }, LOG_INFO, 'Administrator');
                    }
                }
                
                /*if (isset($Call['Mongo']['Read']['maxTimeMS']))
                    $Cursor->maxTimeMS($Call['Mongo']['Read']['maxTimeMS']);*/
                $Cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
                $Data = $Cursor->toArray();

                foreach ($Data as $IX => $Object)
                    unset($Data[$IX]['_id']);
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
        $Cursor = $Call['Link']->command($Call['Command']);
        $Cursor->setTypeMap(['root' => 'array', 'document' => 'array', 'array' => 'array']);
        return $Cursor->toArray();
    });

    setFn ('Count', function ($Call)
    {
        $Call = F::Apply(null, 'Options', $Call);

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

                $Cursor = $Call['Link']->selectCollection($Call['Scope'])->count();
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
            F::Log($Command, LOG_INFO, 'Administrator');
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