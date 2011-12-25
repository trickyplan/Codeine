<?php

    /* Codeine
     * @author BreathLess
     * @description Generate documentation page from contract 
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Service', function ($Call)
    {
        // Чтение контракта
        $Contract = F::loadOptions ($Call['Service']);

        // Для каждой секции, вызов обработчика секции

        $Options = array(
            'Locale'  => 'ru_RU',
            'Service' => $Call['Service']
        );

        $Call['Renderer'] = 'View.Render.HTML';
        $Call['Value']    = array(
            array(
                'Place' => 'Content',
                'Type'  => 'Heading',
                'Level' => 2,
                'Value' => $Call['Service']
            )
        );

        foreach($Contract as $MethodName => $Method)
            $Call['Value'][] =
                array(
                    'Place' => 'Content',
                    'Type'  => 'Heading',
                    'Level' => 4,
                    'Value' => $MethodName,
                    'Subtext' => $Method['Description'][$Options['Locale']]
                );

        return $Call;
    });

    self::setFn ('Method', function ($Call)
    {
        // Чтение контракта
        $Contract = F::loadOptions($Call['Service'], $Call['Method']);

        // Для каждой секции, вызов обработчика секции

        $Options = array(
            'Locale' => 'ru_RU',
            'Service' => $Call['Service'],
            'Method' => $Call['Method']
        );

        $Call['Renderer'] = 'View.Render.HTML';
        $Call['Value'] = array(
            array(
                'Place' => 'Content',
                'Type'  => 'Heading',
                'Level' => 2,
                'Value' => $Call['Service'].'.'.$Call['Method']
            )
        );

        foreach ($Contract[$Call['Method']] as $Section => $Content)
        {
            $Call['Value'][] =
                array(
                            'Place'     => 'Content',
                            'Type'      => 'Heading',
                            'Level'     => 2,
                            'Localized' => true,
                            'Value'     => 'Code.Documentation.Section.'.$Section
                );

            $Call['Value'] = array_merge($Call['Value'],
                                         F::Run ('Code.Documentation.Section.' . $Section, 'Do', array($Section => $Content), $Options));
        }

        // Вывод
        return $Call;
    });