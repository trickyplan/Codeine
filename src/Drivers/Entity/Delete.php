<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeDeleteDo', $Call);

        $Call['Where'] = F::Live($Call['Where']); // FIXME

        return F::Run(null, $_SERVER['REQUEST_METHOD'], $Call);
    });

    self::setFn('GET', function ($Call)
    {
        $Call = F::Hook('beforeDeleteGet', $Call);

        if (!isset($Call['NoEntityLayouts']))
        {
            $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main');
            $Call['Layouts'][] = array('Scope' => 'Entity','ID' => 'Delete');
            $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Delete');
        }

        $Call = F::Run('Entity.Show.Static', 'Do', $Call, array('Template' => 'Short', 'Context' => 'internal'));

        return $Call;
    });

    self::setFn('POST', function ($Call)
    {
        F::Run('Entity', 'Delete', $Call);

        $Call = F::Hook('afterDeletePost', $Call);

        return $Call;
    });