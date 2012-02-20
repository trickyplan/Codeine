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
        $Call = F::Merge($Call, F::Run('IO', 'Open', $Call));
        $Data = F::Run ($Call['Driver'], 'Read', $Call);

        if (isset($Call['Format']))
            $Data = F::Run($Call['Format'], 'Decode', array('Value' => $Data));

        return $Data;
    });

    self::setFn ('Write', function ($Call)
    {
        $Storage = F::Run ('IO', 'Open', $Call);

        if (isset($Call['Format']))
            $Call['Data'] = F::Run ($Call['Format'], 'Encode', array('Value' => $Call['Data']));

        return F::Run ($Storage['Driver'], 'Write', $Call, $Storage);
    });

    self::setFn ('Close', function ($Call)
    {
        $Storage = F::Run ('IO', 'Open', $Call);
        return F::Run ($Storage['Driver'], 'Close', $Call, $Storage);
    });

    self::setFn ('Execute', function ($Call)
    {
        $Storage = F::Run ('IO', 'Open', $Call);
        return F::Run ($Storage['Driver'], $Call['Execute'], $Call, $Storage);
    });