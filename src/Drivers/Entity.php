<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Create', function ($Call)
    {
        $Call = F::Run(null, 'Load', $Call);

        if (isset($Call['Data']['ID']))
            $Call['ID'] = $Call['Data']['ID'];
        else
            $Call['ID'] = F::Run('Security.UID.Integer', 'Get', $Call);

        $Call = F::Run('Code.Flow.Hook', 'Run', $Call, array ('On'=> 'beforeCreate'));

        foreach ($Call['Data'] as $Key => $Value)
            if (null === $Value)
                unset($Call['Data'][$Key]);

        F::Run('IO', 'Write', $Call,
            array (
                  'Scope' => $Call['Entity']
            ));

        F::Run('Code.Flow.Hook', 'Run', $Call, array ('On' => 'afterCreate'));

        return $Call['Data'];
    });

    self::setFn('Read', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        $Call['Data'] = F::Run('IO', 'Read', $Call,
            array (
                  'Scope'   => $Call['Entity']
            ));

        $Call = F::Hook('afterRead', $Call);

        return $Call['Data'];
    });

    self::setFn('Update', function ($Call)
    {
        $Data = F::Run(null, 'Read', $Call);

        $Call = F::Run(null, 'Load', $Call);

        if (isset($Data[0]))
            $Call['Data'] = F::Merge($Data[0], $Call['Data']);

        $Call['Data']['ID'] = $Call['Where']; // FIXME

        $Call = F::Run('Code.Flow.Hook', 'Run', $Call, array ('On'=> 'beforeUpdate'));

        if (isset($Data[0]))
            foreach ($Call['Data'] as $Key => $Value)
                if ((null === $Value) or ($Value == $Data[0][$Key]))
                    unset($Call['Data'][$Key]);

        F::Run('IO', 'Write', $Call,
            array (
                  'Scope' => $Call['Entity']
            ));

        $Call = F::Run('Code.Flow.Hook', 'Run', $Call, array ('On' => 'afterUpdate'));

        return $Call['Data'];
    });

    self::setFn('Delete', function ($Call)
    {
        $Call = F::Run(null, 'Load', $Call);

        F::Run('IO', 'Write', $Call,
            array (
                  'Scope'   => $Call['Entity'],
                  'Data'    => null
            ));

        return $Call;
    });

    self::setFn('Load', function ($Call)
    {
        $Call = F::Run('Code.Flow.Hook', 'Run', $Call, array ('On'=> 'beforeLoad'));

        $Call = F::Merge($Call, F::loadOptions('Entity.'.$Call['Entity']));

        $Call = F::Run('Code.Flow.Hook', 'Run', $Call, array ('On'=> 'afterLoad'));

        return $Call;
    });

    self::setFn('Set', function ($Call)
    {
        $Call = F::Run(null, 'Load', $Call);
        // TODO Atomic hooks
        F::Run('IO', 'Write', $Call,
            array (
                  'Scope' => $Call['Entity']
            ));

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