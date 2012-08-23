<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        $Current = file_get_contents('https://raw.github.com/Breathless/Codeine/master/docs/VERSION');

        $Call['Output']['Content'][] =
            array (
                'Type'  => 'Block',
                'Class' => 'alert ' . ($Current > $Call['Codeine']['Version'] ? 'alert-error' : 'alert-success'), // FIXME
                'Value' => 'Установленная версия: '.$Call['Codeine']['Version']. '<br/> Актуальная версия: ' . $Current
            );

        return $Call;
    });