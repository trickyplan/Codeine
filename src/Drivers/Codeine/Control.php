<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Do', function ($Call)
    {
        $Current = file_get_contents('https://raw.github.com/Breathless/Codeine/master/docs/VERSION');

        $Call['Output']['Content'][] =
            array (
                'Type'  => 'Block',
                'Class' => 'alert ' . ($Current > '7.5.1' ? 'alert-error' : 'alert-success'), // FIXME
                'Value' => 'Установленная версия: 7.5.1. <br/> Актуальная версия: ' . $Current
            );

        return $Call;
    });