<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
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