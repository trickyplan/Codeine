<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Version = phpversion();
        $Version = isset($Call['Versions'][$Version])? $Call['Versions'][$Version]: 'Untested';

        $Call['Output']['Content'] = array (
            array (
                'Type'  => 'Block',
                'Class' => 'alert '.('Stable' == $Version? 'alert-success': 'alert-warning'),
                'Value' => 'PHP: '.phpversion().' <br/> <l>PHP.Control:Version.Verdict.'.$Version.'</l>'
            ));

        return $Call;
     });

    setFn('Extensions', function ($Call)
    {
        $Extensions = get_loaded_extensions();

        foreach ($Extensions as $Extension)
            $ExtensionsRows[] = [
                $Extension,
                phpversion($Extension),
                '<l>PHP.Extension:'.$Extension.'</l>'];


        $Call['Output']['Content'][] =
            array (
                'Type'  => 'Table',
                'Value' => $ExtensionsRows
            );

        return $Call;
    });

    setFn('Settings', function ($Call)
    {
        $INI = ini_get_all(null,false);

        foreach ($INI as $Key => $Value)
        {
            $Data[] = [$Key, nl2br(wordwrap($Value))];

            if (isset($Call['Requirements'][$Key]))
                $Call['Output']['Content'][] =
                    [
                        'Type'  => 'Block',
                        'Class' => 'alert alert-danger',
                        'Value' => '<strong>'.$Key.'</strong> = "'.$Call['Requirements'][$Key].'"'
                    ];
        }

        $Call['Output']['Content'][] =
            [
                'Type'  => 'Table',
                'Value' => $Data
            ];

        return $Call;
    });

    setFn('Menu', function ($Call)
    {
        return ['Count' => phpversion()];
    });