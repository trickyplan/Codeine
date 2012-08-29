<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        if (!isset($Call['Bundle']))
            $Call['Bundle'] = isset($Call['Start'])? $Call['Start']: 'Codeine';

        if (!isset($Call['Option']))
            $Call['Option'] = 'Do';

        $Call['Layouts'][] = array(
            'Scope' => $Call['Bundle'],
            'ID' => 'Control'
        );

        $Call['Layouts'][] = array(
            'Scope' => $Call['Bundle'],
            'ID' => 'Control/'.$Call['Option']
        );

        $Call = F::Run($Call['Bundle'].'.Control', $Call['Option'], $Call);

        foreach($Call['Bundles'] as $Group => $Bundles)
        {
            $Call['Options'][] = $Group;

            if (in_array($Call['Bundle'], $Bundles))
                $Call['Group'] = $Group;

            foreach ($Bundles as $Bundle)
            {
                $Call['Locales'][] = $Bundle.':Control';
                $Call['Options'][] = ['ID' => $Bundle, 'Group' => $Group];
            }
        }

        $Call['Output']['Navigation'][] = array(
            'Type' => 'Navlist',
            'Scope' => 'Control',
            'Options' => $Call['Options'],
            'Value' => $Call['Bundle']
        );

        return $Call;
     });