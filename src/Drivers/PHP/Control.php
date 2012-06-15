<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {

        return $Call;
     });

    self::setFn('Version', function ($Call)
    {
        $Version = phpversion();
        $Version = isset($Call['Versions'][$Version])? $Call['Versions'][$Version]: 'Untested';

        $Call['Output']['Content'] = array (
            array (
                'Type'  => 'Block',
                'Class' => 'alert '.(('Stable' == $Version)? 'alert-success': (('Unstable' == $Version)? 'alert-error': '')),
                'Value' => 'PHP: '.phpversion().' — '.$Version
            ));

        $Extensions = get_loaded_extensions();

        foreach ($Extensions as $Extension)
            $Versions[] = array('<l>PHP.Extension.'.$Extension.'</l>', phpversion($Extension));


        $Call['Output']['Content'][] =
            array (
                'Type'  => 'Table',
                'Value' => $Versions
            );

        return $Call;
    });

    self::setFn('Settings', function ($Call)
    {
        $INI = ini_get_all(null,false);

        foreach ($INI as $Key => $Value)
        {
            $Data[] = array ('<l>PHP.INI.'.$Key.'</l>', nl2br(wordwrap($Value)));

            if (isset($Call['Requirements'][$Key]) && ($Call['Requirements'][$Key] != $Value['local_value']))
                $Call['Output']['Content'][] =
                    array (
                        'Type'  => 'Block',
                        'Class' => 'alert alert-danger',
                        'Value' => 'Recommended value for <strong>'.$Key.'</strong> is "'.$Call['Requirements'][$Key].'"'
                    );
        }

        $Call['Output']['Content'][] =
            array (
                'Type'  => 'Table',
                'Value' => $Data
            );



        return $Call;
    });

    self::setFn('XCache', function ($Call)
    {
        $XCache = xcache_info(XC_TYPE_PHP,0);

        foreach ($XCache as $Key => &$Value)
            $Value = array('<l>PHP.XCache.'.$Key.'</l>', $Value);

        $Call['Output']['Content'][] =
            array (
                'Type'  => 'Table',
                'Value' => $XCache
            );

        return $Call;
    });