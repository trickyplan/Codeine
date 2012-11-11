<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (!isset($Call['Bundle']))
            $Call['Bundle'] = isset($Call['Start'])? $Call['Start']: 'Codeine';

        if (!isset($Call['Action']))
            $Call['Action'] = 'Do';

        if (!isset($Call['Option']))
            $Call = F::Run($Call['Bundle'].'.Report', $Call['Action'], $Call, ['Context' => 'app']);
        else
            $Call = F::Run($Call['Bundle'].'.Report.'.$Call['Option'], $Call['Action'], $Call, ['Context' => 'app']);

        $Call['Layouts'][] = array(
            'Scope' => $Call['Bundle'],
            'ID' => 'Report',
            'Context' => $Call['Context']
        );

        $Call['Layouts'][] = array(
            'Scope' => $Call['Bundle'],
            'ID' => 'Report/'.$Call['Option'],
            'Context' => $Call['Context']
        );

        $Call['Layouts'][] = array(
            'Scope' => $Call['Bundle'],
            'ID' => 'Report/'.$Call['Option'].'/'.$Call['Action'],
            'Context' => $Call['Context']
        );

        foreach($Call['Bundles'] as $Group => $Bundles)
        {
            $Call['Options'][] = $Group;

            if (in_array($Call['Bundle'], $Bundles))
                $Call['Group'] = $Group;

            foreach ($Bundles as $Bundle)
            {
                $Options = ['ID' => $Bundle, 'Group' => $Group];

                if (($BundleOptions = F::Run($Bundle.'.Report', 'Menu', $Call)) !== null)
                    $Options = F::Merge($Options, $BundleOptions);

                $Call['Options'][] = $Options;
            }
        }

        foreach ($Call['Sidebar'] as &$Sidebar)
            $Pills[] = ['ID' => $Sidebar, 'URL' => '/report/'.$Call['Bundle'].'/'.$Sidebar, 'Title' => $Call['Bundle'].'.Report:Options.'.$Sidebar];

        if (!isset($Call['Failure']))
        {
            $Call['Output']['Sidebar'][] = [
                'Type' => 'Navpills',
                'Options' => $Pills,
                'Value' => $Call['Option']
            ];

            $Call['Output']['Navigation'][] = [
                'Type' => 'Navlist',
                'Scope' => 'Report',
                'Options' => $Call['Options'],
                'Value' => $Call['Bundle']
            ];
        }

        return $Call;
     });