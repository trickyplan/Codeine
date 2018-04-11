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
            
            if (empty($Model))
                F::Log('Model for '.$Call['Entity'].' not found', LOG_CRIT);
            else
            {
                if (!isset($Model['EV']))
                    $Model['EV'] = 1;
                
                $Call = F::Merge($Call, $Model);
            }

        if (isset($Call['Nodes']))
            $Call['Nodes'] = F::Sort($Call['Nodes'], 'Weight', SORT_DESC);
        else
            F::Log('Nodes not loaded', LOG_WARNING);

        $Call = F::Hook('afterEntityLoad', $Call);

        $Call['entity'] = strtr(strtolower($Call['Entity']), '.', '/'); // Hm.
        $Call['Flat Entity'] = str_replace('.', '', $Call['Entity']);

        F::Log('Entity '.$Call['Entity'].' Nodes: '.count($Call['Nodes']), LOG_DEBUG);
        
        return $Call;
    });

    setFn('Create', function ($Call)
    {
        if (!isset($Call['Entity']))
        {
            F::Log('Entity not defined.', LOG_ERR);
            return $Call;
        }
        
        if (isset($Call['One']))
        {
            $Call['Internal One'] = $Call['One'];
            unset($Call['One']);
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

        $Call['No Where'] = true;
        $Call = F::Hook('beforeOperation', $Call);

        $NewData = $Call['Data'];
        unset($Call['Data']);

        foreach ($NewData as $IX => $Call['Data'])
        {
            $Call['Current'] = $Call['Data'];
           
            if (isset($Call['Dry']))
                F::Log('Dry shot for '.$Call['Entity'].' create');
            else
            {
                $Call = F::Hook('beforeEntityCreate', $Call);
                    $Call = F::Hook('before'.$Call['Flat Entity'].'Create', $Call);
                        $Call = F::Hook('beforeEntityCreateOrUpdate', $Call);
                            $Call = F::Hook('before'.$Call['Flat Entity'].'CreateOrUpdate', $Call);
                                $Call = F::Hook('beforeEntityWrite', $Call);
                                    $Call = F::Hook('before'.$Call['Flat Entity'].'Write', $Call);
                                    
                                    if (isset($Call['Failure']) and $Call['Failure'])
                                        $Call['Data'] = null;
                                    else
                                    {
                                        $Call['Data'] = F::Run('IO', 'Write', $Call);
                                        
                                                            $Call = F::Hook('afterEntityWrite', $Call);
                                                        $Call = F::Hook('after'.$Call['Flat Entity'].'Write', $Call);
                                                    $Call = F::Hook('afterEntityCreateOrUpdate', $Call);
                                                $Call = F::Hook('after'.$Call['Flat Entity'].'CreateOrUpdate', $Call);
                                            $Call = F::Hook('afterEntityCreate', $Call);
                                        $Call = F::Hook('after'.$Call['Flat Entity'].'Create', $Call);
                                    }
                    
                                    
            }
            $NewData[$IX] = $Call['Data'];
        }

        $Call = F::Hook('afterOperation', $Call);

        F::Log('*'.count($NewData).'* '.$Call['Entity'].' created', LOG_INFO, 'Administrator');

        if (isset($Call['Internal One']))
        {
            $NewData = array_shift($NewData);
            unset($Call['Internal One']);
        }

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
        
        if (isset($Call['One']))
        {
            $Call['Internal One'] = $Call['One'];
            unset($Call['One']);
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

        if (isset($Call['Internal One']) && $Call['Internal One'] && is_array($Call['Data']))
        {
            F::Log('One of *'.count($Call['Data']).'* '.$Call['Entity'].' read.', LOG_INFO, 'Administrator');
            unset($Call['Internal One']);
            $Call['Data'] = array_shift($Call['Data']);
        }
        else
            F::Log('*'.count($Call['Data']).'* '.$Call['Entity'].' read', LOG_INFO, 'Administrator');
        
        // F::Log(j(F::Dot($Call['Data'], 'Tags')), LOG_NOTICE);
        
        return $Call['Data'];
    });

    setFn('Update', function ($Call)
    {
        if (!isset($Call['Entity']))
        {
            F::Log('Entity not defined.', LOG_ERR);
            return null;
        }

        if (isset($Call['One']))
        {
            $Call['Internal One'] = $Call['One'];
            unset($Call['One']);
        }

        $Call = F::Hook('beforeOperation', $Call);

            $Entities = F::Run('Entity', 'Read', $Call, ['One' => false, 'Time' => microtime(true).rand()]);

            if (empty($Entities))
                ;
            else
            {
                $VCall = [];

                foreach ($Entities as $Call['Current'])
                {
                    // Поиск по всем полям
                    $VCall['Where'] = ['ID' => $Call['Current']['ID']];

                    if (F::Dot($Call, 'Data') === null)
                        $Call['Data'] = $Call['Current'];
                    else
                    {
                        foreach ($Call['Nodes'] as $Name => $Node)
                        {
                            $UpdatedValue = F::Dot($Call['Data'], $Name);

                            if (null === $UpdatedValue)
                                if (isset($Node['Nullable']) && $Node['Nullable'])
                                    $Call['Data'] = F::Dot($Call['Data'], $Name, null);
                                else
                                    $Call['Data'] = F::Dot($Call['Data'], $Name, F::Dot($Call['Current'], $Name));
                        }
                    }

                    $Call['Data']['EV'] = $Call['EV'];

                    $Call = F::Hook('beforeEntityUpdate', $Call);
                    $Call = F::Hook('before'.$Call['Flat Entity'].'Update', $Call);
                    $Call = F::Hook('beforeEntityCreateOrUpdate', $Call);
                    $Call = F::Hook('before'.$Call['Flat Entity'].'CreateOrUpdate', $Call);
                    $Call = F::Hook('beforeEntityWrite', $Call);
                    $Call = F::Hook('before'.$Call['Flat Entity'].'Write', $Call);

                    if (isset($Call['Failure']) and $Call['Failure'])
                    {
                        F::Log('Update skipped due Failure Flag: '.j($Call['Errors']), LOG_NOTICE, 'Administrator');
                        $Call['Data'] = null;
                    }
                    else
                    {
                            if (isset($Call['Dry']))
                                F::Log('Dry shot for ' . $Call['Entity'] . ' update');
                            else
                            {
                                F::Run('IO', 'Write', $Call, $VCall);
    
                                $Call = F::Hook('afterEntityWrite', $Call);
                                $Call = F::Hook('after'.$Call['Flat Entity'].'Write', $Call);
                                $Call = F::Hook('afterEntityCreateOrUpdate', $Call);
                                $Call = F::Hook('after'.$Call['Flat Entity'].'CreateOrUpdate', $Call);
                                $Call = F::Hook('afterEntityUpdate', $Call);
                                $Call = F::Hook('after'.$Call['Flat Entity'].'Update', $Call);
                            }
                    }
                }

                $Entities = F::Run('Entity', 'Read', $Call, ['Time' => microtime(true).rand(), 'One' => false]);

                F::Log('*'.count($Entities).'* '.$Call['Entity'].' updated', LOG_INFO, 'Administrator');

                if (isset($Call['Internal One']) && $Call['Internal One'] && is_array($Entities))
                {
                    $Entities = array_shift($Entities);
                    unset($Call['Internal One']);
                }
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

        $Current = F::Run('Entity', 'Read', $Call, ['Time' => microtime(true).rand()]);
        
        $Call = F::Hook('beforeOperation', $Call);

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
                            $Call['Data'] = $Call['Current'];
    
                            $Call = F::Hook('afterEntityWrite', $Call);
                        $Call = F::Hook('afterEntityDelete', $Call);
                }
            }

            $Call = F::Hook('afterOperation', $Call);

            F::Log('*'.count($Current).'* '.$Call['Entity'].' removed', LOG_INFO, 'Administrator');
        }
        else
            F::Log('*0* '.$Call['Entity'].' to delete', LOG_INFO, 'Administrator');

        return $Current;
    });

    setFn('Count', function ($Call)
    {
        if (isset($Call['Entity']))
            ;
        else
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
    
    setFn('Distinct', function ($Call)
    {
        if (isset($Call['Entity']))
            ;
        else
        {
            F::Log('Entity not defined.', LOG_ERR);
            return null;
        }

        $Call = F::Hook('beforeOperation', $Call);
            $Call = F::Hook('beforeEntityDistinct', $Call);

            $Call['Data'] = F::Run('IO', 'Execute', $Call,
            [
                  'Execute' => 'Distinct',
                  'Scope'   => $Call['Entity']
            ]);

            $Call = F::Hook('afterEntityDistinct', $Call);
        $Call = F::Hook('afterOperation', $Call);

        if (empty($Call['Data']))
            $Call['Data'] = 0;

        F::Log('*'.count($Call['Data']).'* '.$Call['Entity'].' read distinction.', LOG_INFO, 'Administrator');

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