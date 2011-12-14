<?php

   /* Codeine
     * @author BreathLess
     * @description  New Data Engine
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Open', function ($Call)
    {
        $Call['Link'] = F::Run($Call['Driver'], 'Open', $Call);
        F::Set('Storage.'.$Call['Storage'], $Call);
        return $Call['Link'];
     });

    self::setFn ('Read', function ($Call)
    {
        $Storage = F::Get ('Storage.' . $Call['Storage']);
        return F::Run ($Storage['Driver'], 'Read', $Call, $Storage);
    });

    self::setFn ('Write', function ($Call)
    {
        $Storage = F::Get ('Storage.' . $Call['Storage']);
        return F::Run ($Storage['Driver'], 'Write', $Call, $Storage);
    });

    self::setFn ('Close', function ($Call)
    {
        $Storage = F::Get ('Storage.' . $Call['Storage']);
        return F::Run ($Storage['Driver'], 'Close', $Call, $Storage);
    });

    self::setFn ('Execute', function ($Call)
    {
        $Storage = F::Get ('Storage.' . $Call['Storage']);
        return F::Run ($Storage['Driver'], 'Execute', $Call, $Storage);
    });