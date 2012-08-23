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

        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Main','Context' => $Call['Context']);
        $Call['Layouts'][] = array('Scope' => $Call['Entity'],'ID' => 'Delete','Context' => $Call['Context']);

        $Call = F::Run('Entity.Show.Static', 'Do', $Call, array('Template' => 'Delete', 'Context' => 'app'));

        return $Call;
    });

    self::setFn('POST', function ($Call)
    {
        F::Run('Entity', 'Delete', $Call);

        $Call = F::Hook('afterDeletePost', $Call);

        $Call['Output'][] = true;

        return $Call;
    });