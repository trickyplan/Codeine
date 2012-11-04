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
            $Data[] = array ('<l>PHP.Ini:'.$Key.'</l>', nl2br(wordwrap($Value)));

            if (isset($Call['Requirements'][$Key]) && ($Call['Requirements'][$Key] != $Value['local_value']))
                $Call['Output']['Content'][] =
                    array (
                        'Type'  => 'Block',
                        'Class' => 'alert alert-danger',
                        'Value' => '<strong>'.$Key.'</strong> = "'.$Call['Requirements'][$Key].'"'
                    );
        }

        $Call['Output']['Content'][] =
            array (
                'Type'  => 'Table',
                'Value' => $Data
            );



        return $Call;
    });

    setFn('XCache', function ($Call)
    {
        $XCache = xcache_info(XC_TYPE_PHP,0);

        foreach ($XCache as $Key => &$Value)
            $Value = array('<l>PHP.XCache:'.$Key.'</l>', $Value);

        $Call['Output']['Content'][] =
            array (
                'Type'  => 'Table',
                'Value' => $XCache
            );

        return $Call;
    });