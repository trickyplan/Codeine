<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        $Current = file_get_contents('https://raw.github.com/Breathless/Codeine/master/docs/VERSION');

        $Call['Output']['Content'][] =
            array (
                'Type'  => 'Block',
                'Class' => 'alert ' . ($Current > '7.4' ? 'alert-error' : 'alert-success'),
                'Value' => 'Установленная версия: 7.4. <br/> Актуальная версия: ' . $Current
            );

        return $Call;
    });