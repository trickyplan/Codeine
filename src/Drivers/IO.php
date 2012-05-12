<?php

    /* Codeine
     * @author BreathLess
     * @description  New IO Engine
     * @package Codeine
     * @version 7.2
     */

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
                $Call  = F::Merge($Call['Storages'][$Alias['Storage']], $Alias);
            }
            else
                return F::Run('Code.Flow.Hook', 'Run', $Call, array ('On'=> 'Storage.NotFound'));
        }

        if (($Call['Link'] = F::Get($StorageID)) === null)
            $Call['Link'] = F::Set($StorageID, F::Run($Call['Driver'], 'Open', $Call));

        return $Call;
     });

    self::setFn ('Read', function ($Call)
    {
        $Call = F::Merge(F::Run('IO', 'Open', $Call), $Call);

        // Если в Where простая переменная - это ID.
        if (isset($Call['Where']) && is_scalar($Call['Where']))
            $Call['Where'] = array('ID' => $Call['Where']);

        if (isset($Call['Driver']))
            $Data = F::Run ($Call['Driver'], 'Read', $Call);
        else
            $Data = null;

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
        $Call = F::Merge(F::Run('IO', 'Open', $Call), $Call);

        return F::Run ($Call['Driver'], 'Execute', $Call);
    });