<?php

    /* Codeine
     * @author BreathLess
     * @description  New IO Engine
     * @package Codeine
     * @version 7.x
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
            {
                F::Log($Call['Storage'].' not found', 'Error');
                return $Call = F::Hook('Storage.NotFound', $Call);
            }
        }

        if (($Call['Link'] = F::Get($StorageID)) === null)
            $Call['Link'] = F::Set($StorageID, F::Run($Call['Driver'], 'Open', $Call));

        return $Call;
     });

    self::setFn ('Read', function ($Call)
    {
        $Call = F::Run('IO', 'Open', $Call);

        // Если в Where простая переменная - это ID.
        if (isset($Call['Where']) && is_scalar($Call['Where']))
            $Call['Where'] = array('ID' => $Call['Where']);

        if (isset($Call['Driver']))
            $Call['Data'] = F::Run ($Call['Driver'], 'Read', $Call);
        else
            $Call['Data'] = null;

        if (isset($Call['Format']) && is_array($Call['Data']))
            foreach($Call['Data'] as &$Element)
                $Element = F::Run($Call['Format'], 'Decode', array ('Value' => $Element));

        return $Call['Data'];
    });

    self::setFn ('Write', function ($Call)
    {
        $Call = F::Run('IO', 'Open', $Call);

        // Если в Where простая переменная - это ID.
        if (isset($Call['Where']) && is_scalar($Call['Where']))
            $Call['Where'] = array ('ID' => $Call['Where']);

        if (isset($Call['Format']))
        {
            $Call['ID'] = $Call['Data']['ID'];
            $Call['Data'] = F::Run ($Call['Format'], 'Encode', array('Value' => $Call['Data']));
        }

        return F::Run ($Call['Driver'], 'Write', $Call);
    });

    self::setFn ('Close', function ($Call)
    {
        $Call = F::Run('IO', 'Open', $Call);
        return F::Run ($Call['Driver'], 'Close', $Call);
    });

    self::setFn ('Execute', function ($Call)
    {
        $Call = F::Run('IO', 'Open', $Call);

        if (isset($Call['Where']) && is_scalar($Call['Where']))
                $Call['Where'] = array('ID' => $Call['Where']);

        return F::Run ($Call['Driver'], $Call['Execute'], $Call);
    });