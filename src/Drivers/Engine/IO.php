<?php

   /* Codeine
     * @author BreathLess
     * @description  New IO Engine
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Open', function ($Call)
    {
        $Call = F::Merge($Call, $Call['Storages'][$Call['Storage']]);

        $Call['Link'] = F::Run($Call['Driver'], 'Open', $Call);
        // F::Set('Storage.'.$Call['Storage'], $Call);
        return $Call;
     });

    self::setFn ('Read', function ($Call)
    {
        $Call = F::Merge($Call, F::Run('Engine.IO', 'Open', $Call));
        $Data = F::Run ($Call['Driver'], 'Read', $Call);

        if (isset($Call['Format']))
            $Data = F::Run($Call['Format'], 'Decode', array('Value' => $Data));

        return $Data;
    });

    self::setFn ('Write', function ($Call)
    {
        $Storage = F::Run ('Engine.IO', 'Open', $Call);

        if (isset($Call['Format']))
            $Call['Data'] = F::Run ($Call['Format'], 'Encode', array('Value' => $Call['Data']));

        return F::Run ($Storage['Driver'], 'Write', $Call, $Storage);
    });

    self::setFn ('Close', function ($Call)
    {
        $Storage = F::Run ('Engine.IO', 'Open', $Call);
        return F::Run ($Storage['Driver'], 'Close', $Call, $Storage);
    });

    self::setFn ('Execute', function ($Call)
    {
        $Storage = F::Run ('Engine.IO', 'Open', $Call);
        return F::Run ($Storage['Driver'], 'Execute', $Call, $Storage);
    });