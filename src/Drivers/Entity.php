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

        $Call = F::Hook('beforeCreate', $Call);

        $Nodes = array_keys($Call['Nodes']);

        foreach ($Call['Data'] as $Key => $Value)
            if (null === $Value or !in_array($Key, $Nodes))
                unset($Call['Data'][$Key]);

        $Call['Data']['ID'] = $Call['ID'];

        F::Run('IO', 'Write', $Call,
            array (
                  'Scope' => $Call['Entity']
            ));

        $Call = F::Hook('afterCreate', $Call);

        return $Call['Data'];
    });

    self::setFn('Read', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        $Call = F::Hook('beforeRead', $Call);

        $Call['Data'] = F::Run('IO', 'Read', $Call,
            array (
                  'Scope'   => $Call['Entity']
            ));

        $Call = F::Hook('afterRead', $Call);

        return $Call['Data'];
    });

    self::setFn('Update', function ($Call)
    {
        $Call = F::Run(null, 'Load', $Call);

        $Data = F::Run(null, 'Read', $Call);

        if (isset($Data[0]))
            $Data = $Data[0];

        $Call['Data']['ID'] = $Call['Where'];

        $Call = F::Run('Code.Flow.Hook', 'Run', $Call, array ('On'=> 'beforeUpdate'));


        $Nodes = array_keys($Call['Nodes']);

        foreach($Call['Data'] as $Key => $Value)
            if ($Value == null or $Data[$Key] == $Value or !in_array($Key, $Nodes))
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

    self::setFn('Count', function ($Call)
    {
        $Call = F::Run(null, 'Load', $Call);

        return F::Run('IO', 'Execute', $Call,
            array (
                  'Execute' => 'Count',
                  'Scope'   => $Call['Entity']
            ));
    });