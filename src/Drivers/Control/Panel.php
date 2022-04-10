<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        if (!isset($Call['Bundle'])) {
            $Call['Bundle'] = isset($Call['Start']) ? $Call['Start'] : 'Project';
        }

        if (!isset($Call['Option'])) {
            $Call['Option'] = 'Do';
        }

        F::Log($Call['Bundle'] . ' ' . $Call['Option'] . ' started', LOG_NOTICE);

        $Call = F::loadOptions($Call['Bundle'] . '.Control', null, $Call);
        $Call = F::Apply($Call['Bundle'] . '.Control', $Call['Option'], $Call);

        $Call['Layouts'][] = [
            'Scope' => $Call['Bundle'],
            'ID' => 'Control'
        ];

        $Navigation = [];

        if (isset($Call['Bundles'])) {
            foreach ($Call['Bundles'] as $Group => $Bundles) {
                if (in_array($Call['Bundle'], $Bundles)) {
                    $Call['Group'] = $Group;
                }

                $GroupOptions = [];

                foreach ($Bundles as $Bundle) {
                    $Options = [
                        'ID' => $Bundle,
                        'Bundle' => strtr($Bundle, '.', '/'),
                        'URL' => '/control/' . $Bundle,
                        'Title' => '<codeine-locale>' . $Bundle . '.Control:Title</codeine-locale>',
                        'Group' => $Group,
                        'Status' => 'default'
                    ];

                    if (isset($Call['Icons'][$Bundle])) {
                        $Options['Icon'] = $Call['Icons'][$Bundle];
                    }

                    if (
                        ($BundleOptions =
                            F::Run($Bundle . '.Control', 'Menu', ['Bundle' => $Bundle])) !== null
                    ) {
                        $Options = F::Merge($Options, $BundleOptions);
                    }

                    $GroupOptions[] = $Options;
                }

                if (count($GroupOptions) > 0) {
                    $Navigation[] = $Group;
                    $Navigation = array_merge($Navigation, $GroupOptions);
                }
            }

            if (isset($Call['Sidebar']) && is_array($Call['Sidebar'])) {
                $Actions = [];

                foreach ($Call['Sidebar'] as &$Sidebar) {
                    $Call['Run'] = [
                        'Service' => 'Control.Panel',
                        'Method' => 'Do',
                        'Call' =>
                            [
                                'Bundle' => $Call['Bundle'],
                                'Option' => $Sidebar
                            ]
                    ];

                    $Actions[] =
                        [
                            'ID' => $Sidebar,
                            'URL' => '/control/' . $Call['Bundle'] . '/' . $Sidebar . '?BackURL=/control/' . $Call['Bundle'],
                            'Title' => '<codeine-locale>' . $Call['Bundle'] . '.Control:Options.' . $Sidebar . '</codeine-locale>'
                        ];
                }

                $Call['Output']['Sidebar'][] =
                    [
                        'Type' => 'Navlist',
                        'Scope' => 'Navlist',
                        'Options!' => $Actions,
                        'Value' => $Call['Option']
                    ];
            }

            $Call['Output']['Navigation'][] = [
                'Type' => 'Navlist',
                'Options' => $Navigation,
                'Scope' => 'Navlist',
                'Value' => $Call['Bundle']
            ];
        }

        return $Call;
    });
