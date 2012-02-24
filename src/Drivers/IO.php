<?php

   /* Codeine
     * @author BreathLess
     * @description  New IO Engine
     * @package Codeine
     * @version 7.2
     */

    // Default caching
    self::setFn ('Open', function ($Call)
    {
        $StorageID = $Call['Storage'];

        if (isset($Call['Storages'][$StorageID]))
            $Call = F::Merge($Call, $Call['Storages'][$StorageID]);
        else
        {
            if (isset($Call['Aliases'][$StorageID]))
            {
                $Alias = $Call['Aliases'][$StorageID];
                $Call = F::Merge ($Call['Storages'][$Alias['Storage']], $Alias);
            }
            else
            {
                return null;
            }
        }

        $Call['Link'] = F::Run($Call['Driver'], 'Open', $Call);
        return $Call;
     });

    self::setFn ('Read', function ($Call)
    {
        $Call = F::Merge(F::Run('IO', 'Open', $Call), $Call);

        // Если в Where простая переменная - это ID.
        if (isset($Call['Where']) && is_scalar($Call['Where']))
            $Call['Where'] = array('ID' => $Call['Where']);

        $Data = F::Run ($Call['Driver'], 'Read', $Call);

        if (isset($Call['Format']))
        {
            if (is_array($Data))
                foreach($Data as &$Element)
                    $Element = F::Run($Call['Format'], 'Decode', array ('Value' => $Element));
            else
                $Data = F::Run($Call['Format'], 'Decode', array('Value' => $Data));
        }

        return $Data;
    });

    self::setFn ('Write', function ($Call)
    {
        $Call = F::Merge(F::Run('IO', 'Open', $Call), $Call);

        // Если в Where простая переменная - это ID.
        if (isset($Call['Where']) && is_scalar($Call['Where']))
            $Call['Where'] = array ('ID' => $Call['Where']);

        if (isset($Call['Format']))
            $Call['Data'] = F::Run ($Call['Format'], 'Encode', array('Value' => $Call['Data']));

        return F::Run ($Call['Driver'], 'Write', $Call);
    });

    self::setFn ('Close', function ($Call)
    {
        $Call = F::Merge(F::Run('IO', 'Open', $Call), $Call);
        return F::Run ($Call['Driver'], 'Close', $Call);
    });

    self::setFn ('Execute', function ($Call)
    {
        // Если в Where простая переменная - это ID.
        if (isset($Call['Where']) && is_scalar($Call['Where']))
            $Call['Where'] = array ('ID' => $Call['Where']);

        $Call = F::Merge(F::Run('IO', 'Open', $Call), $Call);

        return F::Run ($Call['Driver'], $Call['Execute'], $Call);
    });