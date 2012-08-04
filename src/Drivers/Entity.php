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

        F::Log('Trying to loading '.$Call['Entity']);


        if (is_array($Model = F::loadOptions($Call['Entity'].'.Entity')))
            $Call = F::Merge($Model, $Call);
        else
            F::Log('Model for '.$Call['Entity'].'not found');

       // $Call = F::Hook('afterEntityLoad', $Call);

        return $Call;
    });

    self::setFn('Create', function ($Call)
    {
        $Call = F::Hook('beforeEntityCreate', $Call);

        $Call['Scope'] = $Call['Entity'];

        $Call['Data'] = F::Run('IO', 'Write', $Call);

        $Call = F::Hook('afterEntityCreate', $Call);

        return $Call['Data'];
    });

    self::setFn('Read', function ($Call)
    {
        $Call = F::Hook('beforeEntityRead', $Call);

        $Call['Scope'] = $Call['Entity'];

        $Call['Data'] = F::Run('IO', 'Read', $Call);

        $Call = F::Hook('afterEntityRead', $Call);

        return $Call['Data'];
    });

    self::setFn('Update', function ($Call)
    {
        $Call['Current'] = F::Run('Entity', 'Read', $Call)[0];

        $Call = F::Hook('beforeEntityUpdate', $Call);

        $Call['Scope'] = $Call['Entity'];

        $Call['Data'] = F::Run('IO', 'Write', $Call);

        $Call = F::Hook('afterEntityUpdate', $Call);

        return $Call['Data'];
    });

    self::setFn('Delete', function ($Call)
    {
        $Call['Current'] = F::Run('Entity', 'Read', $Call)[0];

        $Call = F::Hook('beforeEntityDelete', $Call);

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

    self::setFn('Set', function ($Call)
    {
        $Call = F::Run(null, 'Load', $Call);

        $Call['Scope'] = $Call['Entity'];

        $Call['Data'] = F::Run('IO', 'Write', $Call);

        return $Call['Data'];
    });