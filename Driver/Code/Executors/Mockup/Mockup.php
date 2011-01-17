<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Mockup Executor
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 24.11.10
     * @time 3:03
     */

    self::Fn('Run', function ($Call)
    {
        $Contract = Code::LoadContract(Code::Prepare($Call['Call']));

        if (isset($Contract['Mockup']))
        {
            foreach ($Contract['Mockup'] as $Suite)
            {
                $Decision = true;

                foreach ($Contract['Arguments'] as $ArgName => $Argument)
                {
                    if ($Suite['Call'][$ArgName] !== $Call['Call'][$ArgName])
                    {
                        $Decision = false;
                        break;
                    }
                }

                if ($Decision)
                    return $Suite['Value'];
            }

            return $Contract['Mockup']['Default']['Value'];
        }
        else
        {
            Code::On('Code', 'errNoMockupDefined', $Call);
            return null;
        }
    });