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

        $Call = F::Run($Call['Bundle'].'.Control', $Call['Option'], $Call);

        $Call['Layouts'][] = array(
            'Scope' => $Call['Bundle'],
            'ID' => 'Control',
            'Context' => $Call['Context']
        );

        $Call['Layouts'][] = array(
            'Scope' => $Call['Bundle'],
            'ID' => 'Control/'.$Call['Option'],
            'Context' => $Call['Context']
        );

        foreach($Call['Bundles'] as $Group => $Bundles)
        {
            if (in_array($Call['Bundle'], $Bundles))
                $Call['Group'] = $Group;

            $GroupOptions = [];

            foreach ($Bundles as $Bundle)
            {
                $Options = ['ID' => $Bundle,
                    'URL' => '/control/'.$Bundle,
                    'Title' => '<l>'.$Bundle.'.Control:Title</l>',
                    'Group' => $Group];

                if (isset($Call['Icons'][$Bundle]))
                    $Options['Icon'] = $Call['Icons'][$Bundle];

                if (($BundleOptions = F::Run($Bundle.'.Control', 'Menu', $Call, ['Bundle' => $Bundle])) !== null)
                    $Options = F::Merge($Options, $BundleOptions);

                $Call['Run'] = [
                        'Service' => 'Control.Panel',
                        'Method'  => 'Do',
                        'Call' =>
                        [
                            'Bundle' => $Bundle,
                            'Option' => 'Do'
                        ]
                    ];

                unset($Call['Decision'],$Call['Weight']);
                $Call = F::Run('Security.Access', 'Check', $Call);

                if ($Call['Decision'])
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
            foreach ($Call['Sidebar'] as &$Sidebar)
            {
                unset($Call['Decision'],$Call['Weight']);

                $Call['Run'] = [
                            'Service' => 'Control.Panel',
                            'Method'  => 'Do',
                            'Call' =>
                            [
                                'Bundle' => $Call['Bundle'],
                                'Option' => $Sidebar
                            ]
                        ];

                $Call = F::Run('Security.Access', 'Check', $Call);

                if ($Call['Decision'])
                    $Pills[] = ['ID' => $Sidebar, 'URL' => '/control/'.$Call['Bundle'].'/'.$Sidebar, 'Title' => $Call['Bundle'].'.Control:Options.'.$Sidebar];
            }

            $Call['Output']['Sidebar'][] =
            [
                'Type' => 'Navpills',
                'Options!' => $Pills,
                'Value' => $Call['Option']
            ];
        }

        $Call['Output']['Navigation'][] = [
            'Type' => 'Navlist',
            'Options' => $Navigation,
            'Value' => $Call['Bundle']
        ];

        return $Call;
     });