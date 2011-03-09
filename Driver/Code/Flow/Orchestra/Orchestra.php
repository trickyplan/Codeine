<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 09.03.11
     * @time 3:06
     */

    self::Fn('Run', function ($Call)
    {

        foreach ($Call['Steps'] as $ID => &$Step)
        {
            array_walk($Step,
                function (&$value, $key, $Call)
                {
                    if (is_string($value) && substr($value, 0,1) == '$')
                        $value = $Call['Result'][substr($value,1)];
                    else
                        $value = Core::Any($value);
                },
                $Call);

            $Call['Result'][$ID] = Code::Run(Code::Current($Step));
        }
                
        return $Call;
    });
