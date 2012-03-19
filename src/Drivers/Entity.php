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

        $Call['Data']['ID'] = F::Live($Call['ID']);

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
        $Call = F::Run(null, 'Load', $Call);

        $Call['Data'] = F::Run('IO', 'Read', $Call,
            array (
                  'Scope'   => $Call['Entity']
            ));

        $Call = F::Run('Code.Flow.Hook', 'Run', $Call, array ('On' => 'afterRead'));

        return $Call['Data'];
    });

    self::setFn('Update', function ($Call)
    {
        $Call = F::Run(null, 'Load', $Call);

        $Call['Data']['ID'] = $Call['Where'];

        $Call = F::Run('Code.Flow.Hook', 'Run', $Call, array ('On'=> 'beforeUpdate'));

        foreach ($Call['Data'] as $Key => $Value)
            if (null === $Value)
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