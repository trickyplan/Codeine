<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::_loadSource('Entity.Control');

    setFn('Delete Others', function ($Call)
    {
        $Call['Layouts'][] = ['Scope' => 'Entity', 'ID' => 'Delete'];
        return F::Apply('Entity.Delete', 'Do', $Call,
            [
                'Entity' => $Call['Bundle'],
                'Scope'  => 'Control',
                'Where'  => ['ID' => ['$ne' => $Call['Session']['ID']]]
            ]);
    });