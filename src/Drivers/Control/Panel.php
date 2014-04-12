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
            $Call['Bundle'] = isset($Call['Start'])? $Call['Start']: 'Project';

        if (!isset($Call['Option']))
            $Call['Option'] = 'Do';

        F::Log($Call['Bundle'].' '.$Call['Option'].' started', LOG_IMPORTANT);

        $Call = F::Apply($Call['Bundle'].'.Control', $Call['Option'], $Call);

        $Call['Layouts'][] = [
            'Scope' => $Call['Bundle'],
            'ID' => 'Control'
        ];

        $Navigation = [];

        F::Log('Control Panel Navigation', LOG_IMPORTANT);

        if (isset($Call['Bundles']))
        {
            foreach($Call['Bundles'] as $Group => $Bundles)
            {
                if (in_array($Call['Bundle'], $Bundles))
                    $Call['Group'] = $Group;

                $GroupOptions = [];

                foreach ($Bundles as $Bundle)
                {
                    $Options = [
                        'ID' => $Bundle,
                        'URL' => '/control/'.$Bundle,
                        'Title' => '<l>'.$Bundle.'.Control:Title</l>',
                        'Group' => $Group,
                        'Status' => 'default'
                    ];

                    if (isset($Call['Icons'][$Bundle]))
                        $Options['Icon'] = $Call['Icons'][$Bundle];

                    if (($BundleOptions =
                            F::Run($Bundle.'.Control', 'Menu', ['Bundle' => $Bundle])) !== null)
                        $Options = F::Merge($Options, $BundleOptions);

                    $GroupOptions[] = $Options;
                }

                if (count($GroupOptions) > 0)
                {
                    $Navigation[] = $Group;
                    $Navigation = array_merge($Navigation, $GroupOptions);
                }
            }

            if (isset($Call['Sidebar']) && is_array($Call['Sidebar']))
            {
                $Tabs = [];
                foreach ($Call['Sidebar'] as &$Sidebar)
                {
                    $Call['Run'] = [
                                'Service' => 'Control.Panel',
                                'Method'  => 'Do',
                                'Call' =>
                                [
                                    'Bundle' => $Call['Bundle'],
                                    'Option' => $Sidebar
                                ]
                            ];

                    $Tabs[] =
                        [
                            'ID' => $Sidebar,
                            'URL' => '/control/'.$Call['Bundle'].'/'.$Sidebar,
                            'Title' => $Call['Bundle'].'.Control:Options.'.$Sidebar];
                }

                $Call['Output']['Sidebar'][] =
                [
                    'Type' => 'Tabs',
                    'Options!' => $Tabs,
                    'Value' => $Call['Option']
                ];
            }

            $Call['Output']['Navigation'][] = [
                'Type' => 'Navlist',
                'Options' => $Navigation,
                'Value' => $Call['Bundle']
            ];
        }

        return $Call;
     });