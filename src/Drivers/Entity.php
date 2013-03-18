<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Load', function ($Call)
    {
            $Call = F::Hook('beforeEntityLoad', $Call);

            $Model = F::loadOptions($Call['Entity'].'.Entity');

            if (!empty($Model))
            {
                F::Log('Model for '.$Call['Entity'].' loaded', LOG_INFO);
                $Call = F::Merge($Model, $Call);
            }
            else
                F::Log('Model for '.$Call['Entity'].' not found', LOG_CRIT);

           $Call = F::Hook('afterEntityLoad', $Call);

        return $Call;
    });

    setFn('Create', function ($Call)
    {
        if (isset($Call['One']))
        {
            $Call['Data'] = [$Call['Data']];
            unset($Call['One']);
        }

        $Call = F::Hook('beforeOperation', $Call);

            $Call = F::Hook('beforeEntityWrite', $Call);

                $Call = F::Hook('beforeEntityCreate', $Call);

            if (!isset($Call['Failure']) or !$Call['Failure'])
            {
                $Call['Data'] = F::Run('IO', 'Write', $Call);

                $Call = F::Hook('afterEntityCreate', $Call);

            $Call = F::Hook('afterEntityWrite', $Call);
            }

        $Call = F::Hook('afterOperation', $Call);

        return $Call;
    });

    setFn('Read', function ($Call)
    {
        $Call = F::Hook('beforeOperation', $Call);

            $Call = F::Hook('beforeEntityRead', $Call);

                $Call['Data'] = F::Run('IO', 'Read', $Call);

            $Call = F::Hook('afterEntityRead', $Call);

        $Call = F::Hook('afterOperation', $Call);

        if (isset($Call['One']))
            return $Call['Data'][0];
        else
            return $Call['Data'];
    });

    setFn('Update', function ($Call)
    {
        if (isset($Call['One']))
        {
            $Call['Data'] = [$Call['Data']];
            unset($Call['One']);
        }

        $Call = F::Hook('beforeOperation', $Call);

            $Call['Current'] = F::Run('Entity', 'Read', $Call);

            $Call['Data'] = F::Merge($Call['Current'],$Call['Data']);

            $Call = F::Hook('beforeEntityWrite', $Call);

                $Call = F::Hook('beforeEntityUpdate', $Call);

                    $Call['Data'][0]['ID'] = $Call['Where']['ID'];

                    if (!empty($Call['Data']))
                        $Call['Data'] = F::Run('IO', 'Write', $Call);

                $Call = F::Hook('afterEntityUpdate', $Call);

            $Call = F::Hook('afterEntityWrite', $Call);

        $Call = F::Hook('afterOperation', $Call);


        return $Call;
    });

    setFn('Delete', function ($Call)
    {
        $Call['Data'] = F::Run('Entity', 'Read', $Call);
        $Call['Current'] = $Call['Data'];

        $Call = F::Hook('beforeOperation', $Call);

            $Call = F::Hook('beforeEntityWrite', $Call);

                $Call = F::Hook('beforeEntityDelete', $Call);

                    unset($Call['Data']);

                    F::Run('IO', 'Write', $Call, ['Data' => [null]]);

                    $Call['Data'] = $Call['Current'];

                $Call = F::Hook('afterEntityDelete', $Call);

        $Call = F::Hook('afterEntityWrite', $Call);

        $Call = F::Hook('afterOperation', $Call);

        return $Call;
    });

    setFn('Count', function ($Call)
    {
        $Call = F::Hook('beforeOperation', $Call);
            $Call = F::Hook('beforeEntityCount', $Call);

        $Call['Data'] = F::Run('IO', 'Execute', $Call,
            [
                  'Execute' => 'Count',
                  'Scope'   => $Call['Entity']
            ]);

            $Call = F::Hook('afterEntityCount', $Call);
        $Call = F::Hook('afterOperation', $Call);

        return $Call['Data'];
    });

    setFn('Far', function ($Call)
    {
        $Element = F::Run(null, 'Read', $Call)[0];
        return isset($Element[$Call['Key']])? $Element[$Call['Key']]: null;
    });