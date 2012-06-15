<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        if (!isset($Call['Bundle']))
            $Call['Bundle'] = isset($Call['Start'])? $Call['Start']: 'Main';

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

        foreach($Call['Bundles'] as $Group => $Bundles)
        {
            $Call['Options'][] = '<l>Control.'.$Group.'</l>';

            foreach ($Bundles as $Bundle)
                $Call['Options'][] = array('ID' => $Bundle, 'Title' => '<l>Control.'.$Bundle.'</l>', 'URL' => '/control/'.$Bundle);
        }

        $Call['Output']['Navigation'][] = array(
            'Type' => 'Navlist',
            'Scope' => 'Control',
            'Options' => $Call['Options'],
            'Value' => $Call['Bundle']
        );

        return $Call;
     });