<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Merge($Call, F::loadOptions($Call['Entity'].'.Entity'));

        $Element = F::Run('Entity', 'Read', $Call);

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Widgets']['Show']))
                $Call['Output']['Content'][] =
                    F::Merge($Node['Widgets']['Show'], ['Key' => $Name, 'Value' => $Element[0][$Name]]);

        }

        $Call['Front']['Entity'] = $Call['Entity']; //FIXME

        return $Call;
    });