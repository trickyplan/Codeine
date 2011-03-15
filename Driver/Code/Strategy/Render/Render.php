<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Render Selector
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 23:28
     */

    self::Fn('Select', function ($Call)
    {
        $ParentCall = Code::Parent();
        $ExtensionMap = $Call['Contract']['Options']['ExtensionsMap'];

        if (is_array($ParentCall['Input']) && isset($ParentCall['Input']['Format']))
        {
            if (isset($ExtensionMap[$ParentCall['Input']['Format']]))
                return $ExtensionMap[$ParentCall['Input']['Format']];
        }
        else
            return 'Codeine';
    });
