<?php

    /* Codeine
     * @author BreathLess
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

        $Call = F::Hook('beforeEntityLoad', $Call);

            $Model = F::loadOptions($Call['Entity'].'.Entity');

            if (!empty($Model))
                $Call = F::Merge($Model, $Call);
            else
                F::Log('Model for '.$Call['Entity'].' not found', LOG_CRIT);

        $Call = F::Hook('afterEntityLoad', $Call);

        $Call['entity'] = strtolower($Call['Entity']); // Hm.

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
            $Call['Data'] = [$Call['Data']];
            unset($Call['One']);
        }

        $Call = F::Hook('beforeOperation', $Call);

            $Data = $Call['Data'];

            $Call['Data'] = null;

            foreach ($Data as $cData)
            {
                $Call['Data'] = $cData;

                    $Call = F::Hook('beforeEntityWrite', $Call);

                    $Call = F::Hook('beforeEntityCreate', $Call);

                    if (!isset($Call['Failure']) or !$Call['Failure'])
                    {
                        $Call['Data'] = F::Run('IO', 'Write', $Call);

                        $Call = F::Hook('afterEntityCreate', $Call);
                    }
                    else
                        $Call['Data'] = null;

                    $Call = F::Hook('afterEntityWrite', $Call);
            }

        $Call = F::Hook('afterOperation', $Call);

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        if (!isset($Call['Entity']))
        {
            F::Log('Entity not defined.', LOG_ERR);
            return null;
        }

        $Call = F::Hook('beforeOperation', $Call);

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

        $Call = F::Hook('afterOperation', $Call);

        F::Log('*'.count($Call['Data']).'* '.$Call['Entity'].' readed', LOG_INFO);

        if (isset($Call['One']) and is_array($Call['Data']))
            return array_shift($Call['Data']);
        else
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
            $Call['Data'] = [$Call['Data']];
            unset($Call['One']);
        }

        $Call = F::Hook('beforeOperation', $Call);

        $Current = F::Run('Entity', 'Read', $Call, ['ReRead' => true]);

        $NewData = $Call['Data'];

        $Result = [];

        foreach ($Current as $IX => $OldData)
        {
            if (isset($NewData[$IX]))
                $Call['Data'] = $NewData[$IX];
            else
                $Call['Data'] = $NewData;

            $Call['Where'] = ['ID' => $OldData['ID']];
            $Call['Data'] = F::Merge($OldData, $Call['Data']);
            $Call['Current'] = $OldData;

            $Call = F::Hook('beforeEntityWrite', $Call);

                $Call = F::Hook('beforeEntityUpdate', $Call);

                    F::Run('IO', 'Write', $Call);

                $Call = F::Hook('afterEntityUpdate', $Call);

            $Call = F::Hook('afterEntityWrite', $Call);

            $Result[$IX] = $Call['Data'];
        }

        $Call['Data'] = $Result;
        $Call = F::Hook('afterOperation', $Call);

        F::Log('*'.count($Call['Data']).'* '.$Call['Entity'].' updated', LOG_INFO);

        return $Call;
    });

    setFn('Delete', function ($Call)
    {
        if (!isset($Call['Entity']))
        {
            F::Log('Entity not defined.', LOG_ERR);
            return null;
        }

        if (isset($Call['Where']))
        {
            $Call['Data'] = F::Run('Entity', 'Read', $Call);
            $Call['Current'] = $Call['Data'];
        }
        else
            $Call['Data'] = [null];

        $Data = $Call['Data'];

            $Call = F::Hook('beforeOperation', $Call);

                $Call = F::Hook('beforeEntityDelete', $Call);

        if ($Data !== null)
            foreach ($Data as $cData)
            {
                $Call['Data'] = $cData;
                        unset($Call['Data']);

                        F::Run('IO', 'Write', $Call);

                        if(isset($Call['Where']))
                            $Call['Data'] = $Call['Current'];

                $Call = F::Hook('afterEntityDelete', $Call);
            }

        $Call = F::Hook('afterOperation', $Call);

        return $Call;
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

        F::Log('*'.$Call['Data'].'* '.$Call['Entity'].' counted.', LOG_INFO);

        return $Call['Data'];
    });

    setFn('Far', function ($Call)
    {
        $Element = F::Run(null, 'Read', $Call, ['One' => true]);
        return isset($Element[$Call['Key']])? $Element[$Call['Key']]: null;
    });