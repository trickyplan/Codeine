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
            'Scope' => 'Codeine',
            'ID' => 'Version'
        ];

        $Current = file_get_contents('https://raw.github.com/Breathless/Codeine/master/docs/VERSION');

        $Call['Output']['Content'][] =
             [
                 'Type'  => 'Block',
                 'Class' => 'alert alert-info',
                 'Value' => '<l>Codeine.Control:Version.Actual</l>:<br/> ' . $Current
             ];

        $Call['Output']['Content'][] =
            [
                'Type'  => 'Block',
                'Class' => 'alert ' . ($Current > $Call['Version']['Codeine']['Major'] ? 'alert-error' : 'alert-success'),
                'Value' => '<l>Codeine.Control:Version.Installed</l>: <br/>'.$Call['Version']['Codeine']['Major']
            ];

        return $Call;
    });

    setFn('Menu', function ($Call)
    {
        $Call['Version'] = F::loadOptions('Version');

        if (isset($Call['Version']['Codeine']))
            return ['Count' => $Call['Version']['Codeine']['Major'].'.'.$Call['Version']['Codeine']['Minor']];
    });