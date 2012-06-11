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

        $Call = F::Hook('beforeCreate', $Call);

            $Nodes = array_keys($Call['Nodes']);

            foreach ($Call['Data'] as $Key => $Value)
                if (null === $Value or !in_array($Key, $Nodes))
                    unset($Call['Data'][$Key]);

        $Call['Data'] = F::Run('IO', 'Write', $Call,
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

        $OldData = F::Run(null, 'Read', $Call)[0];

        $Call['Data']['ID'] = $Call['Where'];

        $Call = F::Run('Code.Flow.Hook', 'Run', $Call, array ('On'=> 'beforeUpdate'));

        $Nodes = array_keys($Call['Nodes']);

        foreach ($Call['Data'] as $Key => $Value)
                if ($Value == $OldData[$Key] or empty($Value) or !in_array($Key, $Nodes))
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
        $Call = F::Hook('beforeLoad', $Call);

        $Call = F::Merge($Call, F::loadOptions('Entity.'.$Call['Entity']));

        $Call = F::Hook('afterEntityLoad', $Call);

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