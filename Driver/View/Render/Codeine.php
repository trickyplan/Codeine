<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: D8 Port
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 16.11.10
     * @time 3:48
     */

    self::Fn('Do', function ($Call)
    {
        // Определить тип данных

        // Контроллер отдаёт набор UI компонентов

        $Layout = Data::Read('Layout::Main');

        if (isset($Call['Input']['Layouts']) && is_array($Call['Input']['Layouts']))
            foreach ($Call['Input']['Layouts'] as $Inner)
                $Layout = str_replace('<content/>', Data::Read('Layout::'.$Inner), $Layout);

        $Output = array();
        
        // Обработка контролов

        if (is_array($Call['Input']['Items']))
        {
            foreach ($Call['Input']['Items'] as $ID => $Item)
            {
                $Output[$ID] = Code::Run(
                    array_merge_recursive(
                        array(
                            'N'=>'View.UI.Codeine.'.$Item['UI'],
                            'F' => 'Make'),$Item)
                );
            }

            $Output = implode('',$Output);
        }
        else
            $Output = $Call['Input'];

        // Постпроцессинг

        $Output = str_replace('<content/>',$Output, $Layout);

        $Processors = $Call['Contract']['Processors'];

        foreach ($Processors as $Processor)
            $Output = Code::Run(
                array(
                     'N' => 'View.Processors.'.$Processor,
                     'F' => 'Process',
                     'Input'=> $Output)
            );

        // Вернуть



        return $Output;
    });
