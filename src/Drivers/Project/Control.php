<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Project',
            'ID' => 'Status'
        ];

        if (F::file_exists(Root.'/Options/Version.json'))
            $Call['Project']['MTime'] = filemtime(Root.'/Options/Version.json');

        return $Call;
    });

    setFn('Menu', function ($Call)
    {
        $Call['Version'] = F::loadOptions('Version');
        if (isset($Call['Version']))
            return ['Count' => $Call['Version']['Project']['Major'].'.'.$Call['Version']['Project']['Minor']];
        else
            return null;
    });