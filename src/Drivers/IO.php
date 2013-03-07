<?php

    /* Codeine
     * @author BreathLess
     * @description  New IO Date
     * @package Codeine
     * @version 7.x
     */

    setFn ('Open', function ($Call)
    {
        $StorageID = $Call['Storage'];

        if (!isset($Call['Storage']))
        {
            return F::Log('IO Null Storage: ', LOG_ERR);
        }

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
                trigger_error($Call['Storage'].' not found'); // FIXME
            }
        }

        if (($Call['Link'] = F::Get($StorageID)) === null)
            $Call['Link'] = F::Set($StorageID, F::Run($Call['Driver'], 'Open', $Call));

        /*if ($Call['Link'] === null)
            $Call = F::Hook('Storage.Open.Failed', $Call);*/

        return $Call;
     });

    setFn ('Read', function ($Call)
    {
        if (isset($Call['Result']))
            unset($Call['Result']);

        $Call = F::Run('IO', 'Open', $Call);

        $Call = F::Hook('beforeIORead', $Call);

        if (!isset($Call['Result']))
        {
            // Если в Where простая переменная - это ID.
            if (isset($Call['Where']) && is_scalar($Call['Where']))
                $Call['Where'] = ['ID' => $Call['Where']];

            if (isset($Call['Driver']))
                $Call['Result'] = F::Run ($Call['Driver'], 'Read', $Call);
            else
                $Call['Result'] = null;

            if (isset($Call['Format']) && is_array($Call['Result']))
            {
                foreach($Call['Result'] as &$Element)
                    $Element = F::Run($Call['Format'], 'Decode', ['Value' => $Element]);
            }

            $Call = F::Hook('afterIORead', $Call);
        }

        if (isset($Call['One']) && $Call['One'])
            return $Call['Result'][0];

        return $Call['Result'];
    });

    setFn ('Write', function ($Call)
    {
        $Call = F::Run('IO', 'Open', $Call);

        // Если в Where простая переменная - это ID.
        if (isset($Call['Where']) && is_scalar($Call['Where']))
            $Call['Where'] = ['ID' => $Call['Where']];

        if (isset($Call['Format']))
        {
            if (isset($Call['Data']))
                $Call['ID'] = $Call['Data']['ID'];

            $Call['Data'] = F::Run ($Call['Format'], 'Encode', ['Value' => $Call['Data']]);
        }

        $Call['Data'] = F::Run ($Call['Driver'], 'Write', $Call);

        if (isset($Call['Format']))
            $Call['Data'] = F::Run ($Call['Format'], 'Decode', ['Value' => $Call['Data']]);

        return $Call['Data'];
    });

    setFn ('Close', function ($Call)
    {
        $Call = F::Run('IO', 'Open', $Call);
        return F::Run ($Call['Driver'], 'Close', $Call);
    });

    setFn ('Execute', function ($Call)
    {
        $Call = F::Run('IO', 'Open', $Call);

        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where']);

        if (isset($Call['Where']) && is_scalar($Call['Where']))
            $Call['Where'] = array('ID' => $Call['Where']);

        return F::Run ($Call['Driver'], $Call['Execute'], $Call);
    });