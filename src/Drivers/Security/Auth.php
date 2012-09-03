<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Audit', function ($Call)
    {
        $Call['SID'] = F::Run($Call['Source'], 'Read', $Call);

        if (empty($Call['SID']))
            $Call['Session'] = F::Run(null, 'Register', $Call);
        else
        {
            $Call['Session'] =
                F::Run('Entity', 'Read',
                    array(
                         'Entity' => 'Session',
                         'Where' => $Call['SID']
                    ))[0];

            if ($Call['Session'] == null)
                if (!isset($Call['No Auto Register']) || !$Call['No Auto Register'])
                    $Call['Session'] = F::Run(null, 'Register', $Call);

            if (isset($Call['Heartbeat']) && $Call['Heartbeat'])
                F::Run('Entity', 'Update',
                    array(
                         'Entity' => 'Session',
                         'Where' => $Call['SID'],
                         'Data' =>
                             ['Heartbeat' => F::Run('System.Time', 'Get')]
                    ));

            if (isset($Call['Session']['User']) && !empty($Call['Session']['User']) && $Call['Session']['User'] !== -1)
            {
                if ($Call['Session']['Expire'] < time())
                {
                    $Call = F::Run(null, 'Annulate', $Call);
                    F::Log('Session expired');
                }
                else
                {
                    if (isset($Call['Session']['Secondary']) &&  -1 !== $Call['Session']['Secondary'])
                        $User = F::Run('Entity', 'Read',
                            array(
                                 'Entity' => 'User',
                                 'Where' => $Call['Session']['Secondary']
                            ))[0];
                    else
                        $User = F::Run('Entity', 'Read',
                            array(
                                 'Entity' => 'User',
                                 'Where' => $Call['Session']['User']
                            ))[0];

                    $Call['Session']['User'] = $User;
                }
            }
        }

        return $Call;
    });

    self::setFn('Register', function ($Call)
    {
        $Call['Session'] = F::Run('Entity', 'Create', $Call,
            array(
                 'Entity' => 'Session',
                 'Data' => []
            ));

        F::Run($Call['Source'], 'Write', $Call);

        return $Call['Session'];
    });

    self::setFn('Annulate', function ($Call)
    {
        F::Run($Call['Source'], null , $Call);

        F::Run('Entity', 'Delete',
                        array(
                             'Entity' => 'Session',
                             'Where' => $Call['SID']
                        ));

        F::Log('Session annulated');

        return $Call;
    });

    self::setFn('Attach', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);

        if (isset($Call['Session']['User']['ID']))
        {
            F::Log('Secondary logon');
            return F::Run('Entity', 'Update',
                array(
                    'Entity' => 'Session',
                    'Where' => $Call['SID'],
                    'Data' =>
                    array(
                        'Secondary' => $Call['User'],
                        'Expire' => time()+$Call['TTL']) // FIXME
                ));
        }
        else
        {
            F::Log('Primary logon');
            return F::Run('Entity', 'Update',
                array(
                    'Entity' => 'Session',
                    'Where' => $Call['SID'],
                    'Data' =>
                    array(
                        'User' => $Call['User'],
                        'Expire' => time()+$Call['TTL']) // FIXME
                ));
        }
    });

    self::setFn('Detach', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);

        if (isset($Call['Session']['Secondary']) && !empty($Call['Session']['Secondary']) && $Call['Session']['Secondary'] !== -1)
            $Call['Session'] = F::Run('Entity', 'Update',
             array(
                  'Entity' => 'Session',
                  'Where' => $Call['SID'],
                  'Data' =>
                  [
                      'Secondary' => -1
                  ]
             ));
        else
            $Call['Session'] = F::Run('Entity', 'Update',
             array(
                  'Entity' => 'Session',
                  'Where' => $Call['SID'],
                  'Data' =>
                  array(
                      'User' => -1
                  )
             ));

        return $Call;
    });

    self::setFn('SID', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);
        return $Call['SID'];
    });

    self::setFn('Username', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);
        return isset($Call['Session']['User']['ID'])? $Call['Session']['User']['ID']: null;
    });

    self::setFn('Write', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);

        return F::Run('Entity', 'Update',
             array(
                  'Entity' => 'Session',
                  'Where' => $Call['SID'],
                  'Data' => $Call['Data'])
             );
    });

    self::setFn('Read', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);
        return isset($Call['Session'][$Call['Key']])? $Call['Session'][$Call['Key']]: null;
    });