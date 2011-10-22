<?php

    /* Codeine
     * @author BreathLess
     * @description Create Action
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Do', function ($Call)
        {
            if (isset($Call['Value']['IMDF']))
            {
                $ID = F::Run ($Call, array('_F' => 'Submit'));
                header ('Location: /Show/' . $Call['Scope'] . '/' . $ID); // FIXME
            }

            $Model   = F::Run ($Call,
                               array(
                                    '_N' => 'Engine.Object',
                                    '_F' => 'Model.Load'
                               ));

            $Call['Widgets'] = array(
                array(
                    'Place'  => 'Content',
                    'Type'   => 'Element',
                    'Widget' => 'Form.Panel',
                    'Action' => '',
                    'ID'     => 'CreateForm',
                    'Value'  => '<place>Create.Form</place>'
                )
            ); // FIXME Dirty Container Hack

            foreach ($Model['Nodes'] as $Name => $Node)
            {
                if ($Node['Tags'] == 'Create' || in_array ('Create', $Node['Tags']))
                {
                    $Editor    = isset($Node['Editor']) ? $Node['Editor'] : 'Textfield';
                    $Call['Widgets'][] =
                        array(
                            'Place'  => 'Create.Form',
                            'Type'   => 'Element',
                            'Widget' => 'Form.' . $Editor,
                            'ID'     => strtr ($Name, '.', '_'),
                            'Class'  => array('Textfield', $Name),
                            'Name'   => $Name
                        );
                }
            }

            $Call['Widgets'][] = array(
                'Place'   => 'Sidebar',
                'Type'    => 'Sidebar.Button',
                'Value'   => 'Ready',
                'Action'  => 'javascript:$(\'#CreateForm\').submit()',
                'Anchor'  => '/join',
                'Subtext' => 'Create.' . $Call['Scope'] . '.Ready.Subtext'
            );

            $Call['Widgets'][] = array(
                'Place'   => 'Sidebar',
                'Type'    => 'Sidebar.Button',
                'Value'   => 'Reset',
                'Action'  => '/',
                'Anchor'  => '/',
                'Subtext' => 'Create.' . $Call['Scope'] . '.Reset.Subtext'
            );

            $Call['Widgets'][] =
                array(
                    'Place'  => 'Create.Form',
                    'Type'   => 'Element',
                    'Widget' => 'Form.Hidden',
                    'Name'   => 'IMDF',
                    'Value'  => uniqid ()
                ); // FIXME Imitodefence

            return $Call;
        });

    self::Fn ('Submit', function ($Call)
        {
            $ID = uniqid ();

            if (isset($Call['Hooks']['after' . $Call['_F']]))
                foreach ($Call['Hooks']['after' . $Call['_F']] as $Hook)
                    $Call = F::Run (F::Merge ($Hook, array('Value' => $Call)), F::Kernel);

            if (F::Run (
                array(
                     'Object' => array('Create', $Call['Scope']),
                     'ID'     => $ID,
                     'Value'  => $Call['Value'])
            ))
            {
                F::Run(
                    array(
                         'Object' => array('Node.Add', 'User'),
                         'ID' => $Call['Session']['Owner'],
                         'Key' => 'Own',
                         'Value' => $Call['Scope'].'::'.$ID
                    )
                );
            }

            return $ID;
        });

    self::Fn ('Do.Sidebar', function ($Call)
        {
            return array(
                            'Place'   => 'Sidebar',
                            'Type'    => 'Sidebar.Button',
                            'Value'   => $Call['_N'].'.'.$Call['Entity'],
                            'Action'  => '/Create/Person',
                            'Subtext' => 'Create.Person.Subtext'
                        );
        });