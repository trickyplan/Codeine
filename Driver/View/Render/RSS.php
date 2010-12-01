<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: RSS Renderer
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 16.11.10
     * @time 4:04
     */

    self::Fn('Do', function ($Call)
    {
        $Output = '';

        foreach($Call['Input']['Items'] as $Item)
        {
            if ($Item['UI'] == 'Object')
            {
                $Output.= 'rss> '.$Item['Entity'].$Item['ID'];
            }
        }

        return $Output;
    });