<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: D8 Port
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 16.11.10
     * @time 3:48
     */

    self::Fn('Render', function ($Call)
    {
        // Определить тип данных

        // Контроллер отдаёт набор UI компонентов

        $Layout = Data::Read(
                array(
                    'Point' => 'Layout',
                    'Where' =>
                        array(
                            'ID'=>'Main')));

        $Output = '';
        
        if (is_array($Call['Input']['Items']))
            foreach ($Call['Input']['Items'] as $Item)
                $Output.= Code::Run(
                    array('F'=>'View/UI/Codeine/Make',
                          'D' => $Item ['UI'],
                          'Item'=> $Item)
                );

        // Вытащить шаблоны

        // Профьюзить
        // Постпроцессинг
        // Вернуть

        $Output = str_replace('<content/>',$Output, $Layout);

        $Processors = array('Media'); // FIXME!!

        foreach ($Processors as $Processor)
                $Output = Code::Run(
                    array('F'=>'View/Processors/'.$Processor.'/Process',
                          'Body'=> $Output)
                );

        return $Output;
    });