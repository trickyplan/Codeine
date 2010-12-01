<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: XML Renderer
     * @package Codeine
     * @subpackage Drivers
     * @version 0
     * @date 16.11.10
     * @time 3:48
     */

    self::Fn('Do', function ($Call)
    {
        header('Content-type: text/xml;charset=utf-8');
        $Output = '<?xml version="1.0" encoding="UTF-8" ?>';

        $Output.= '<root>';

        if (is_array($Call['Input']['Items']))
            foreach($Call['Input']['Items'] as $Item)
            {
                if ($Item['UI'] == 'Object')
                {
                    $Output.= '<item>';
                        foreach ($Item['Data'] as $Key => $Value)
                            $Output.= '<'.$Key.'>'.$Value.'</'.$Key.'>';
                    $Output.= '</item>';
                }
            }
        
        $Output.= '</root>';

        return $Output;
    });