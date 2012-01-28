<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Audit', function ($Call)
        {
            // Получить идентификатор сессии

            $Call = F::Run ($Call, $Call['Source']);

            // Получить сессию

            $Call['Session'] = F::Run (
                array(
                     '_N'      => 'Engine.Object',
                     '_F'      => 'Load',
                     'Scope'   => 'Session',
                     'ID'      => $Call['Auth']['Session']
                )
            );

            if ($Call['Session'])
            {
                foreach ($Call['Validators'] as $Validator)
                    if (!F::Run ($Call, array('_N' => 'Security.Auth.Session.Validator.' . $Validator)))
                        return F::Run ($Call, array('_N' => 'Code.Flow.Hook',
                                                   '_F'  => 'Run',
                                                   'On' => 'Session.Illegal'));
            }
            else
                F::Run ($Call, array('_N' => 'Code.Flow.Hook',
                                    '_F'  => 'Run',
                                    'On'  => 'Session.Nonexist'));

            // Выдать ключ

            if (isset($Call['Session']['Owner']))
            {
                $Call['Owner'] = F::Run (
                    array(
                         '_N'      => 'Engine.Object',
                         '_F'      => 'Load',
                         'Scope'   => 'User',
                         'Where'   =>
                            array('ID' => $Call['Session']['Owner'])
                    )
                );
            }

            return $Call;
        });

    self::setFn ('Register', function ($Call)
        {
            $SID = F::Run (array('_N' => 'Security.UID.GUID', '_F'  => 'Get'));

            F::Run (
                array(
                     '_N'       => 'Security.Auth.Session.Source.Cookie', // OPTME
                     '_F'       => 'Set',
                     'Session' => $SID, // OPTME,
                     'Seal'    => F::Run (array('_N' => 'Security.Auth.Seal.UserAgent',
                                               '_F'  => 'Generate')))
            );

            F::Run (
                array(
                     '_N'      => 'Engine.Object',
                     '_F'      => 'Create',
                     'Scope'   => 'Session',
                     'ID'    => $SID,
                     'Value' => array(
                         'CreatedOn' => time ()
                     )
                )
            );

            return $Call;
        });

    self::setFn ('Annulate', function ($Call)
        {
            F::Run (
                array(
                     '_N' => 'Security.Auth.Session.Source.Cookie', // OPTME
                     '_F' => 'Annulate')
            );

            return $Call;
        });

    self::setFn ('Set', function ($Call)
        {
            $SubCall = F::Run ($Call,
                               array(
                                    '_N' => 'Security.Auth.Session.Source.Cookie', // OPTME
                                    '_F' => 'Get'
                               )
            );

            F::Run (
                array(
                     '_N'  =>  'Engine.Object',
                     '_F'  => 'Node.Set',
                     'Scope' => 'Session',
                     'Where' => array('ID' => $SubCall['Auth']['Session']),
                     'Value' => array(
                         'UpdatedOn' => time ()
                     )
                )
            );

            return $Call;
        });

    self::setFn ('Bind', function ($Call)
        {
            d(__FILE__, __LINE__, $Call);
            $SubCall = F::Run ($Call,
                               array(
                                    '_N' => 'Security.Auth.Session.Source.Cookie', // OPTME
                                    '_F' => 'Get'
                               )
            );

            F::Run (
                array(
                     '_N'    => 'Engine.Object',
                     '_F'    => 'Node.Set',
                     'Scope' => 'Session',
                     'ID'    => $SubCall['Auth']['Session'],
                     'Value' => array('Owner' => $Call['ID'])
                )
            );

            return $Call;
        });

    