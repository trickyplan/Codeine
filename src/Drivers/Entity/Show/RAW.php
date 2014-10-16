<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Merge($Call, F::loadOptions($Call['Entity'].'.Entity')); // FIXME

        $Call = F::Hook('beforeShow', $Call);

        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where'], $Call);

        $Call['Limit'] = ['From' => 0, 'To' => 1];

        if (!isset($Call['Data']))
            $Call['Data'] = F::Run('Entity', 'Read', $Call)[0];

        $Call['Layouts'][] = ['Scope' => $Call['Entity'],'ID' => 'Show','Context' => $Call['Context']];

        $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];

        if (empty($Call['Data']))
            $Call = F::Hook('NotFound', $Call);
        else
        {
            $Call['Output']['Content'][] = '<pre>'.json_encode($Call['Data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'</pre>';

            $Call = F::Hook('afterShow', $Call);
        }

        F::Log($Call['Data'], LOG_INFO);
        return $Call;
    });
