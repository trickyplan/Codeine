<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        // TODO Realize "Do" function

        $Current = file_get_contents('https://raw.github.com/Breathless/Codeine/master/docs/VERSION');

        $Call['Output']['Content'][] = array (
            array (
                'Type'  => 'Block',
                'Class' => 'alert ' . ($Current > '7.2' ? 'alert-error' : 'alert-success'),
                'Value' => 'Установленная версия: 7.2. <br/> Актуальная версия: ' . $Current
            ));



        return $Call;
    });