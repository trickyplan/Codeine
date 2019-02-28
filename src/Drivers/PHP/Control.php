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
            'Scope' => 'PHP',
            'ID' => 'Version'
        ];

        $Version = phpversion();
        
        if (($Tilda = mb_strpos($Version, '~')) === false)
            ;
        else
            $Version = mb_substr($Version, $Tilda);

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
        $VersionNumber = PHP_VERSION_ID;
        $Version = isset($Call['Versions'][$VersionNumber])? $Call['Versions'][$VersionNumber]: 'Untested';

        return ['Count' => $VersionNumber, 'Status' => ('Stable' == $Version? 'success': 'warning')];
    });

    setFn('Realpath.Cache', function ($Call)
    {
        // Thanks to samdark
        $Used = realpath_cache_size();
        $Available = ini_get('realpath_cache_size');

        $Char = strtolower(substr($Available, -1, 1));
        $Available = substr($Available, 0, strlen($Available)-1);

        switch ($Char)
        {
            case 'k':
                $Available *= 1024;
            break;

            case 'm':
                $Available *= 1024*1024;
            break;

            case 'g':
                $Available *= 1024*1024*1024;
            break;
        }

        $TTL = ini_get('realpath_cache_ttl');
        $Ratio = round(($Used / $Available) * 100);

        $Call['Output']['Content'][] =
                    [
                        'Type'  => 'Table',
                        'Value' =>
                        [
                            ['Used, bytes', $Used],
                            ['Available, bytes', $Available],
                            ['TTL, sec', $TTL],
                            ['Ratio, %', $Ratio]
                        ]
                    ];
        return $Call;
    });

    setFn('Globals', function ($Call)
    {

        foreach ($_SERVER as $Key => $Value)
            $Rows[] = [
                '$_SERVER['.$Key.']',
                $Value
            ];


        $Call['Output']['Content'][] =
            array (
                'Type'  => 'Table',
                'Value' => $Rows
            );

        return $Call;
    });
    
    setFn('Opcache', function ($Call)
    {
        return F::Run('PHP.Control.Opcache', 'Do', $Call);
    });