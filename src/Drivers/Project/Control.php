<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Project',
            'ID' => 'Status'
        ];

        if (F::file_exists(Root.'/Options/Version.json'))
            $Call[$Call['Project']['ID']]['MTime'] = filemtime(Root.'/Options/Version.json');

        return $Call;
    });

    setFn('Menu', function ($Call)
    {
        $Call['Project'] = F::Live(F::loadOptions('Project'));
        $Call['Version'] = F::loadOptions('Version');
        if (isset($Call['Version']))
            return ['Count' => $Call['Version'][$Call['Project']['ID']]['Major']];
        else
            return null;
    });