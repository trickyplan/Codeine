<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Load', function ($Call)
    {

        $Call = F::Hook('beforeOperation', $Call);

            $Call = F::Hook('beforeLoad', $Call);

            if (is_array($Model = F::loadOptions($Call['Entity'].'.Entity')))
                $Call = F::Merge($Model, $Call);
            else
                F::Log('Model for '.$Call['Entity'].'not found', LOG_ERR);

           // $Call = F::Hook('afterEntityLoad', $Call);

        $Call = F::Hook('afterOperation', $Call);

        return $Call;
    });

    setFn('Create', function ($Call)
    {
        $Call['Scope'] = $Call['Entity'];

        $Call = F::Hook('beforeOperation', $Call);
            $Call = F::Hook('beforeEntityWrite', $Call);
                $Call = F::Hook('beforeEntityCreate', $Call);

            if (!isset($Call['Failure']))
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
        $Call['Scope'] = $Call['Entity'];

        $Call = F::Hook('beforeOperation', $Call);

        // Если в Where скалярная переменная - это ID.
        if (isset($Call['Where']) && is_scalar($Call['Where']))
            $Call['Where'] = ['ID' => $Call['Where']];

            $Call = F::Hook('beforeEntityRead', $Call);

                $Call['Data'] = F::Run('IO', 'Read', $Call);

            $Call = F::Hook('afterEntityRead', $Call);

        $Call = F::Hook('afterOperation', $Call);

        return $Call['Data'];
    });

    setFn('Update', function ($Call)
    {
        $Call['Scope'] = $Call['Entity'];

        $Call = F::Hook('beforeOperation', $Call);

            // Если в Where скалярная переменная - это ID.
            if (isset($Call['Where']) && is_scalar($Call['Where']))
                $Call['Where'] = ['ID' => $Call['Where']];

            $Call['Current'] = F::Run('Entity', 'Read', $Call, ['Purpose' => 'Update'])[0];

            $Call['Data'] = F::Merge($Call['Current'],$Call['Data']);

            $Call['Data']['ID'] = $Call['Where']['ID'];

            $Call = F::Hook('beforeEntityWrite', $Call);

                $Call = F::Hook('beforeEntityUpdate', $Call);

                    if (!empty($Call['Data']))
                        $Call['Data'] = F::Run('IO', 'Write', $Call);

                $Call = F::Hook('afterEntityUpdate', $Call);

            $Call = F::Hook('afterEntityWrite', $Call);

        $Call = F::Hook('afterOperation', $Call);


        return $Call;
    });

    setFn('Delete', function ($Call)
    {
        $Call['Scope'] = $Call['Entity'];

        $Call = F::Hook('beforeOperation', $Call);

            // Если в Where скалярная переменная - это ID.
                if (isset($Call['Where']) && is_scalar($Call['Where']))
                    $Call['Where'] = array('ID' => $Call['Where']);


            $Call = F::Hook('beforeEntityDelete', $Call);

                $Call = F::Hook('beforeEntityWrite', $Call);

                    $Call['Current'] = F::Run('Entity', 'Read', $Call, ['From Delete' => true])[0];

                    $Call['Data'] = null;

                    F::Run('IO', 'Write', $Call);

                    $Call['Data'] = $Call['Current'];

                $Call = F::Hook('afterEntityWrite', $Call);

            $Call = F::Hook('afterEntityDelete', $Call);

        $Call = F::Hook('afterOperation', $Call);

        return $Call;
    });

    setFn('Count', function ($Call)
    {
        $Call['Scope'] = $Call['Entity'];

        $Call = F::Hook('beforeOperation', $Call);

        $Call = F::Run(null, 'Load', $Call);

        $Call['Data'] = F::Run('IO', 'Execute', $Call,
            [
                  'Execute' => 'Count',
                  'Scope'   => $Call['Entity']
            ]);

        $Call = F::Hook('afterOperation', $Call);

        return $Call['Data'];
    });

    setFn('Far', function ($Call)
    {
        $Element = F::Run(null, 'Read', $Call)[0];
        return isset($Element[$Call['Key']])? $Element[$Call['Key']]: null;
    });