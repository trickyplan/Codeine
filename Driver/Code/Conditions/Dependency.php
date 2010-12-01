<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Dependency Checker
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 30.10.10
     * @time 5:31
     */


    self::Fn('Check', function ($Call)
    {
        if (isset($Call['Contract']['Depends']))
        {
            if (isset($Call['Contract']['Depends']['External']))
            {
                foreach ($Call['Contract']['Depends']['External'] as $Dependency)
                    if (!extension_loaded($Dependency))
                    {
                        self::On(__CLASS__, 'errExternalDependencyFailed', $Call['Contract']);
                        return false;
                    }
            }

            if (isset($Call['Contract']['Depends']['Internal']))
            {
                foreach ($Call['Contract']['Depends']['Internal'] as $Dependency)
                    if (self::Run(array('F' => $Dependency), Code::Ring1, 'Test'))
                    {
                        self::On(__CLASS__, 'errInternalDependencyFailed', $Call['Contract']);
                        return false;
                    }
            }
        }
        return true;
    });