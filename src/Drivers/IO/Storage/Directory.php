<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.2
     * @date 13.08.11
     * @time 22:37
     */

    self::setFn ('Open', function ($Call)
    {
        return $Call['Directory'];
    });

    self::setFn ('Read', function ($Call)
    {
        if (!isset($Call['Scope']))
            $Call['Scope'] = '';

        $Suffix = isset($Call['Suffix'])? $Call['Suffix']: '';
        $Prefix= isset($Call['Prefix'])? $Call['Prefix'] : '';

        $Call['Where']['ID'] = (array) $Call['Where']['ID'];

        foreach ($Call['Where']['ID'] as &$ID)
            $ID = $Call['Link'] . '/' . $Call['Scope'] . '/' . $Prefix. $ID. $Suffix;

        if (isset($Call['Debug']))
            d(__FILE__, __LINE__, $Call['Where']['ID']);

        $Filename = F::findFile($Call['Where']['ID'] );

        if (file_exists ($Filename))
            return file_get_contents ($Filename);
        else
            return null;
    });

    self::setFn ('Write', function ($Call)
    {
        if (!isset($Call['Scope']))
            $Call['Scope'] = '';

        $Suffix   = isset($Call['Suffix']) ? $Call['Suffix'] : '';
        $Prefix   = isset($Call['Prefix']) ? $Call['Prefix'] : '';

        $Filename = Root.'/'.$Call['Link'] . '/' . $Call['Scope'] . '/' . $Prefix . $Call['Where']['ID'] . $Suffix;

        if (isset($Call['Debug']))
            d(__FILE__, __LINE__, $Call['Where']['ID']);

        if (isset($Call['Data']))
            return file_put_contents ($Filename, $Call['Data']);
        else
            return unlink ($Filename);
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Version', function ($Call)
    {
        if (!isset($Call['Scope']))
            $Call['Scope'] = '';

        $Suffix   = isset($Call['Suffix']) ? $Call['Suffix'] : '';
        $Prefix   = isset($Call['Prefix']) ? $Call['Prefix'] : '';

        $Filename = F::findFile ($Call['Link'] .'/'. $Call['Scope'] . '/' . $Prefix . $Call['Where']['ID'] . $Suffix);

        if (file_exists ($Filename))
            return filemtime($Filename);
        else
            return null;
    });

    self::setFn ('Exist', function ($Call)
    {
        if (!isset($Call['Scope']))
            $Call['Scope'] = '';

        $Suffix   = isset($Call['Suffix']) ? $Call['Suffix'] : '';
        $Prefix   = isset($Call['Prefix']) ? $Call['Prefix'] : '';

        $Filename = F::findFile ($Call['Link'] . '/' . $Call['Scope'] . '/' . $Prefix . $Call['Where']['ID'] . $Suffix);

        return file_exists ($Filename);
    });