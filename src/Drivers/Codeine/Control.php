<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        $Call['Layouts'][] =
            [
                'Scope' => 'Codeine',
                'ID' => 'Version'
            ];

        $Current = file_get_contents('https://raw.github.com/Breathless/Codeine/master/docs/VERSION');

        $Call['Output']['Content'][] =
            [
                'Type' => 'Block',
                'Class' => 'alert alert-info',
                'Value' => '<codeine-locale>Codeine.Control:Version.Actual</codeine-locale>:<br/> ' . $Current
            ];

        $Call['Output']['Content'][] =
            [
                'Type' => 'Block',
                'Class' => 'alert ' . ($Current > $Call['Version']['Codeine'] ? 'alert-error' : 'alert-success'),
                'Value' => '<codeine-locale>Codeine.Control:Version.Installed</codeine-locale>: <br/>' . $Call['Version']['Codeine']
            ];

        return $Call;
    });

    setFn('Menu', function ($Call) {
        $Call['Version'] = F::loadOptions('Version');

        if (isset($Call['Version']['Codeine'])) {
            return ['Count' => $Call['Version']['Codeine']];
        } else {
            return null;
        }
    });