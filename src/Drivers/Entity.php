<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Load', function ($Call)
    {
        if (!isset($Call['Entity']))
        {
            F::Log('Entity not defined.', LOG_ERR);
            return null;
        }

        $Call['Nodes'] = $Call['Default Nodes'];

        $Call = F::Hook('beforeEntityLoad', $Call);

            $Model = F::loadOptions($Call['Entity'].'.Entity'); // FIX Validate

            if (!empty($Model))
            {
                if (!isset($Model['EV']))
                    $Model['EV'] = 1;

                $Call = F::Merge($Call, $Model);
            }
            else
            {
                F::Log('Model for '.$Call['Entity'].' not found', LOG_CRIT);
            }

        if (isset($Call['Nodes']))
            $Call['Nodes'] = F::Sort($Call['Nodes'], 'Weight', SORT_DESC);
        else
            F::Log('Nodes not loaded', LOG_WARNING);

        $Call = F::Hook('afterEntityLoad', $Call);

        $Call['entity'] = strtr(strtolower($Call['Entity']), '.', '/'); // Hm.

        return $Call;
    });

    setFn('Create', function ($Call)
    {
        $Data = [];

        if (!isset($Call['Entity']))
        {
            F::Log('Entity not defined.', LOG_ERR);
            return $Call;
        }

        if (isset($Call['Data'][0]))
            ;
        else
            {
                if (isset($Call['Data']))
                    $Call['Data'] = [$Call['Data']];
                else
                    $Call['Data'] = [];
            }

        $Call = F::Hook('beforeOperation', $Call);

        $NewData = $Call['Data'];
        unset($Call['Data']);

            foreach ($NewData as $IX => $Call['Data'])
            {
                if (isset($Call['Failure']) and $Call['Failure'])
                    $Call['Data'] = null;
                else
                {
                    if (isset($Call['Dry']))
                        F::Log('Dry shot for '.$Call['Entity'].' create');
                    else
                    {
                        $Call = F::Hook('beforeEntityCreate', $Call);
                        $Call = F::Hook('beforeEntityCreateOrUpdate', $Call);
                        $Call = F::Hook('beforeEntityWrite', $Call);
                        
                            $Call['Data'] = F::Run('IO', 'Write', $Call);

                        $Call = F::Hook('afterEntityWrite', $Call);
                        $Call = F::Hook('afterEntityCreateOrUpdate', $Call);
                        $Call = F::Hook('afterEntityCreate', $Call); // FIXME All block?
                    }
                }
                $NewData[$IX] = $Call['Data'];
            }

        $Call = F::Hook('afterOperation', $Call);

        F::Log('*'.count($NewData).'* '.$Call['Entity'].' created', LOG_INFO, 'Administrator');

        if (isset($Call['One']))
            $NewData = array_shift($NewData);

        if (isset($Call['Errors']))
            return ['Errors' => $Call['Errors']];
        else
            return $NewData;
    });

    setFn('Read', function ($Call)
    {
        if (!isset($Call['Entity']))
        {
            F::Log('Entity not defined.', LOG_ERR);
            return null;
        }

        $Call = F::Hook('beforeOperation', $Call);
         
            if (isset($Call['Skip Read']))
                F::Log('Read for '.$Call['Entity'].' fully skipped', LOG_NOTICE, 'Performance');
            else
            {
                F::Log('Start reading from *'.$Call['Entity'].'*', LOG_INFO, 'Administrator');

                $Call = F::Hook('beforeEntityRead', $Call);
                    
                $Call['Data'] = F::Run('IO', 'Read', $Call);

                if ($Call['Data'] !== null)
                {
                    $Data = $Call['Data'];

                    $Call['Data'] = null;

                    foreach ($Data as &$cData)
                    {
                        $Call['Data'] = $cData;
                        $Call = F::Hook('afterEntityRead', $Call);
                        $cData = $Call['Data'];
                    }

                    $Call['Data'] = $Data;
                }
            }

        $Call = F::Hook('afterOperation', $Call);

        if (isset($Call['One']) && $Call['One'] && is_array($Call['Data']))
        {
            F::Log('One of *'.count($Call['Data']).'* '.$Call['Entity'].' read', LOG_INFO, 'Administrator');
            return array_shift($Call['Data']);
        }
        else
        {
            F::Log('*'.count($Call['Data']).'* '.$Call['Entity'].' read', LOG_INFO, 'Administrator');
            return $Call['Data'];
        }
    });

    setFn('Update', function ($Call)
    {
        if (!isset($Call['Entity']))
        {
            F::Log('Entity not defined.', LOG_ERR);
            return null;
        }

        if (isset($Call['One']) && $Call['One'])
        {
            unset($Call['One']);
            $One = true;
        }
        else
            $One = false;

        $Call = F::Hook('beforeOperation', $Call);

            $Entities = F::Run('Entity', 'Read', $Call, ['One' => false, 'Time' => microtime(true).rand()]);

            // Если присутствуют такие объекты
            if (empty($Entities))
                ;
            else
            {
                if (isset($Call['Data']))
                    $Call['Updates'] = $Call['Data'];
                else
                    $Call['Updates'] = [];

                $VCall = [];

                foreach ($Entities as $Call['Current']) {
                    $Call['Data'] = [];
                    // Поиск по всем полям
                    $VCall['Where'] = ['ID' => $Call['Current']['ID']];

                    if (empty($Call['Updates']))
                        $Call['Data'] = $Call['Current'];
                    else {
                        if (isset($Call['Data']))
                            ;
                        else
                            $Call['Data'] = [];

                        foreach ($Call['Nodes'] as $Name => $Node) {
                            $UpdatedValue = F::Dot($Call['Updates'], $Name);

                            if (null === $UpdatedValue) {
                                if (isset($Node['Nullable']) && $Node['Nullable'])
                                    $Call['Data'] = F::Dot($Call['Data'], $Name, null);
                                else
                                    $Call['Data'] = F::Dot($Call['Data'], $Name, F::Dot($Call['Current'], $Name));
                            } else
                                $Call['Data'] = F::Dot($Call['Data'], $Name, F::Dot($Call['Updates'], $Name));
                        }
                    }

                    $Call['Data']['EV'] = $Call['EV'];

                    $Call = F::Hook('beforeEntityUpdate', $Call);
                    $Call = F::Hook('beforeEntityCreateOrUpdate', $Call);
                    $Call = F::Hook('beforeEntityWrite', $Call);
                    /*
                    TODO: необходимо щепитильно проверить обновлялку
                    */
                    if (isset($Call['Failure']) and $Call['Failure'])
                    {
                        F::Log('Update Skipped due Failure Flag', LOG_WARNING, 'Administrator');
                        $Call['Data'] = null;
                    }
                    else
                    {
                            if (isset($Call['Dry']))
                                F::Log('Dry shot for ' . $Call['Entity'] . ' update');
                            else {
                                F::Run('IO', 'Write', $Call, $VCall);

                                $Call = F::Hook('afterEntityWrite', $Call);
                                $Call = F::Hook('afterEntityCreateOrUpdate', $Call);
                                $Call = F::Hook('afterEntityUpdate', $Call);
                            }
                    }
                }

                $Entities = F::Run('Entity', 'Read', $Call, ['Time' => microtime(true).rand(), 'One' => false]);

                F::Log('*'.count($Entities).'* '.$Call['Entity'].' updated', LOG_INFO, 'Administrator');

                if ($One && is_array($Entities))
                    $Entities = array_shift($Entities);
            }

        F::Hook('afterOperation', $Call);

        if (isset($Call['Errors']))
            return ['Errors' => $Call['Errors']];
        else
            return $Entities;
    });

    setFn('Delete', function ($Call)
    {
        if (isset($Call['Entity']))
            ;
        else
        {
            F::Log('Entity not defined.', LOG_ERR);
            return null;
        }

        $Call = F::Hook('beforeOperation', $Call);

        $Current = F::Run('Entity', 'Read', $Call, ['Time' => microtime(true).rand()]);

        if ($Current)
        {
            foreach ($Current as $Call['Current'])
            {
                $Call['Data'] = $Call['Current'];

                if (isset($Call['Current']['ID']))
                {
                    $Call['Where'] = $Call['Current']['ID'];
                    
                        $Call = F::Hook('beforeEntityDelete', $Call);
                            $Call = F::Hook('beforeEntityWrite', $Call);
                        
                            unset($Call['Data']);
                            F::Run('IO', 'Write', $Call);
    
                            $Call = F::Hook('afterEntityWrite', $Call);
                        $Call = F::Hook('afterEntityDelete', $Call);
                }
            }

            $Call = F::Hook('afterOperation', $Call);

            F::Log('*'.count($Current).'* '.$Call['Entity'].' removed', LOG_INFO, 'Administrator');
        }

        return $Current;
    });

    setFn('Count', function ($Call)
    {
        if (!isset($Call['Entity']))
        {
            F::Log('Entity not defined.', LOG_ERR);
            return null;
        }

        $Call = F::Hook('beforeOperation', $Call);
            $Call = F::Hook('beforeEntityCount', $Call);

            $Call['Data'] = F::Run('IO', 'Execute', $Call,
            [
                  'Execute' => 'Count',
                  'Scope'   => $Call['Entity']
            ]);

            $Call = F::Hook('afterEntityCount', $Call);
        $Call = F::Hook('afterOperation', $Call);

        if (empty($Call['Data']))
            $Call['Data'] = 0;

        F::Log('*'.$Call['Data'].'* '.$Call['Entity'].' counted.', LOG_INFO, 'Administrator');

        return $Call['Data'];
    });

    setFn('Exist', function ($Call)
    {
        $Element = F::Run(null, 'Read', $Call, ['One' => true]);
        return !empty($Element);
    });

    setFn('Far', function ($Call)
    {
        $Result = null;

        $Element = F::Run(null, 'Read', $Call);

        if (count($Element) == 1)
        {
            $Element = array_pop($Element);
            $Result = isset($Element[$Call['Key']])? $Element[$Call['Key']]: false;
        }
        elseif (count($Element) > 1)
        {
            $Result = [];
            foreach ($Element as $cElement)
                $Result[$cElement['ID']] = isset($cElement[$Call['Key']])? $cElement[$Call['Key']]: false;
        }

        return $Result;
    });