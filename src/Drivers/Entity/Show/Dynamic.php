<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Merge($Call, F::loadOptions('Entity.'.$Call['Entity']));

        $Element = F::Run('Entity', 'Read', $Call);

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (!isset($Node['Widgets']['Show']))
                $Node['Widgets']['Show'] = $Call['Widgets']['Default'];

            $Call['Output']['Content'][] =
                    F::Merge($Node['Widgets']['Show'], array('Key' => $Name, 'Value' => $Element[0][$Name]));

        }

        $Call['Front']['Entity'] = $Call['Entity']; //FIXME

        return $Call;
    });