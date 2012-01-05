<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 13.08.11
     * @time 22:37
     */

    self::setFn ('Open', function ($Call)
    {
        return $Call['Directory'] . '/';
    });

    self::setFn ('Read', function ($Call)
    {
        $Suffix = isset($Call['Suffix'])? $Call['Suffix']: '';
        $Prefix= isset($Call['Prefix'])? $Call['Prefix'] : '';
        $Filename = F::findFile($Call['Link'] . $Call['Scope'] . '/' . $Prefix . $Call['Where']['ID'] . $Suffix);

        if (file_exists ($Filename))
            return file_get_contents ($Filename);
        else
            return null;
    });

    self::setFn ('Write', function ($Call)
    {
        // TODO Validations
        if (isset($Call['Data']))
            return file_put_contents ($Call['Link'] . $Call['Scope'] . '/' . $Call['Where']['ID'], $Call['Data']);
        else
            unlink ($Call['Link'] . $Call['Scope'] . '/' . $Call['Where']['ID']);
    });

    self::setFn ('Close', function ($Call)
    {
        return true;
    });

    self::setFn ('Execute', function ($Call)
    {
        return true;
    });