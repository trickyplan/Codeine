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
                'Class' => 'alert ' . ($Current > $Call['Version']['Codeine']['Major'] ? 'alert-error' : 'alert-success'), // FIXME
                'Value' => 'Установленная версия: '.$Call['Version']['Codeine']['Major']. '<br/> Актуальная версия: ' . $Current
            );

        return $Call;
    });