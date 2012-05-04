<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        foreach($Call['Bundles'] as &$Bundle)
            $Bundle = array('Title' => '<l>Control.'.$Bundle.'</l>', 'URL' => '/control/'.$Bundle);

        $Call['Output']['Navigation'][] = array(
            'Type' => 'Navlist',
            'Value' => $Call['Bundles']
        );

        if (isset($Call['Bundle']))
        {
            if (!isset($Call['Option']))
                $Call['Option'] = 'Do';

            $Call['Locales'][] = $Call['Bundle'].':Control';

            $Call['Layouts'][] = array(
                'Scope' => $Call['Bundle'],
                'ID' => 'Control'
            );

            $Call['Layouts'][] = array(
                'Scope' => $Call['Bundle'],
                'ID' => 'Control/'.$Call['Option']
            );

            $Call = F::Run($Call['Bundle'].'.Control', $Call['Option'], $Call);
        }
        else
        {
            $Call['Bundle']= 'Main';

            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Block',
                    'Class' => 'alert alert-info',
                    'Value' => 'Выберите пакет для настройки'
                );
        }


        return $Call;
     });