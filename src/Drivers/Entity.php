<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    self::setFn('Load', function ($Call)
    {
        $Call = F::Hook('beforeLoad', $Call);

        if (is_array($Model = F::loadOptions($Call['Entity'].'.Entity')))
            $Call = F::Merge($Model, $Call);
        else
            trigger_error('Model for '.$Call['Entity'].'not found');

       // $Call = F::Hook('afterEntityLoad', $Call);

        return $Call;
    });

    self::setFn('Create', function ($Call)
    {
        $Call = F::Hook('beforeEntityCreate', $Call);
        $Call = F::Hook('beforeEntityWrite', $Call);

        $Call['Scope'] = $Call['Entity'];

        $Call['Data'] = F::Run('IO', 'Write', $Call);

        $Call = F::Hook('afterEntityWrite', $Call);
        $Call = F::Hook('afterEntityCreate', $Call);

        return $Call['Data'];
    });

    self::setFn('Read', function ($Call)
    {
        // Если в Where простая переменная - это ID.
            if (isset($Call['Where']) && is_scalar($Call['Where']))
                $Call['Where'] = array('ID' => $Call['Where']);

        $Call = F::Hook('beforeEntityRead', $Call);

        $Call['Scope'] = $Call['Entity'];

        $Call['Data'] = F::Run('IO', 'Read', $Call);

        $Call = F::Hook('afterEntityRead', $Call);

        return $Call['Data'];
    });

    self::setFn('Update', function ($Call)
    {
        // Если в Where простая переменная - это ID.
        if (isset($Call['Where']) && is_scalar($Call['Where']))
            $Call['Where'] = array('ID' => $Call['Where']);

        $Call['Current'] = F::Live(F::Run('Entity', 'Read', $Call, ['From Update' => true])[0]);

        $Call = F::Hook('beforeEntityWrite', $Call);
        $Call = F::Hook('beforeEntityUpdate', $Call);

        $Call['Scope'] = $Call['Entity'];

        $Call['Data'] = F::Run('IO', 'Write', $Call);

        $Call = F::Hook('afterEntityUpdate', $Call);
        $Call = F::Hook('afterEntityWrite', $Call);

        return $Call['Data'];
    });

    self::setFn('Delete', function ($Call)
    {
        // Если в Where простая переменная - это ID.
            if (isset($Call['Where']) && is_scalar($Call['Where']))
                $Call['Where'] = array('ID' => $Call['Where']);


        $Call = F::Hook('beforeEntityDelete', $Call);

        $Call['Current'] = F::Run('Entity', 'Read', $Call)[0];

        $Call['Scope'] = $Call['Entity'];

        F::Run('IO', 'Write', $Call);

        $Call['Data'] = $Call['Current'];

        $Call = F::Hook('afterEntityDelete', $Call);

        return $Call;
    });

    self::setFn('Count', function ($Call)
    {
        $Call = F::Run(null, 'Load', $Call);

        return F::Run('IO', 'Execute', $Call,
            array (
                  'Execute' => 'Count',
                  'Scope'   => $Call['Entity']
            ));
    });

    self::setFn('Far', function ($Call)
    {
        $Element = F::Run(null, 'Read', $Call)[0];
        return isset($Element[$Call['Key']])? $Element[$Call['Key']]: null;
    });