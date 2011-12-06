<?php

   /* Codeine
     * @author BreathLess
     * @description  New Data Engine
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Open', function ($Call)
    {
        $Call['Link'] = F::Run($Call);
        return F::Set('Storage.'.$Call['Storage'], $Call);
     });

    self::setFn ('Read', function ($Call)
    {
        $Storage = F::Get ('Storage.' . $Call['Storage']);
        return F::Run ($Call, $Storage, array('_N' => $Storage['Driver']));
    });

    self::setFn ('Write', function ($Call)
    {
        $Storage = F::Get ('Storage.' . $Call['Storage']);
        return F::Run ($Call, $Storage, array('_N' => $Storage['Driver']));
    });

    self::setFn ('Close', function ($Call)
    {
        $Storage = F::Get ('Storage.' . $Call['Storage']);
        return F::Run ($Call, $Storage, array('_N' => $Storage['Driver']));
    });

    self::setFn ('Execute', function ($Call)
    {
        $Storage = F::Get ('Storage.' . $Call['Storage']);
        return F::Run ($Call, $Storage, array('_N' => $Storage['Driver']));
    });