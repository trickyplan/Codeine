<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Audit', function ($Call)
    {
        $Call['SID'] = F::Run($Call['Source'], 'Read', $Call);

        if (empty($Call['SID']))
            $Call['Session'] = F::Run(null, 'Register', $Call);
        else
        {
            $Call['Session'] =
                F::Run('Entity', 'Read',
                    [
                         'Entity' => 'Session',
                         'Where' => $Call['SID'],
                         'NoMemo' => true
                    ])[0];

            if ($Call['Session'] == null)
                {
                    if (isset($Call['Auto Register']) && $Call['Auto Register'])
                        $Call['Session'] = F::Run(null, 'Register', $Call);
                }

            if (isset($Call['Heartbeat']) && $Call['Heartbeat'])
                F::Run('Entity', 'Update',
                    array(
                         'Entity' => 'Session',
                         'Where' => $Call['SID'],
                         'Data' =>
                             ['Heartbeat' => F::Run('System.Time', 'Get')]
                    ));

            if (isset($Call['Session']['User']) && !empty($Call['Session']['User']) && $Call['Session']['User'] != -1)
            {
                $Call['Headers']['Cache-Control:'] = 'private';

                if ($Call['Session']['Expire'] < time())
                {
                    $Call = F::Run(null, 'Annulate', $Call);
                    F::Log('Session expired', LOG_INFO);
                }
                else
                {
                    if (isset($Call['Session']['Secondary']) && null != $Call['Session']['Secondary'])
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

    setFn('Register', function ($Call)
    {
        $Call = F::Hook('beforeRegister', $Call);

        $Call['Session'] = F::Run('Entity', 'Create', $Call,
            [
                 'Entity' => 'Session',
                 'Data' => isset($Call['Data'])? $Call['Data']: []
            ])['Data'];

        F::Run($Call['Source'], 'Write', $Call);

        $Call = F::Hook('afterRegister', $Call);

        return $Call['Session'];
    });

    setFn('Annulate', function ($Call)
    {
        F::Run($Call['Source'], null , $Call);

        F::Run('Entity', 'Delete',
                        array(
                             'Entity' => 'Session',
                             'Where' => $Call['SID']
                        ));

        setcookie('nocache', null, 0 , '/');
        F::Log('Session annulated', LOG_INFO);

        return $Call;
    });

    setFn('Attach', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call, ['Auto Register' => true]);

        setcookie('nocache', true); // FIXME

        if (!isset($Call['TTL']) or ($Call['TTL'] == 0))
            $Call['TTL'] = 86400;

        if (isset($Call['Session']['User']['ID']))
        {
            F::Log('Secondary logon', LOG_INFO);
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
            F::Log('Primary logon', LOG_INFO);
            return F::Run('Entity', 'Update',
                [
                    'Entity' => 'Session',
                    'Where' => $Call['SID'],
                    'Data' =>
                    [
                        'User' => $Call['User'],
                        'Expire' => time()+$Call['TTL'] // FIXME
                    ]
                ]);
        }
    });

    setFn('Detach', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);

        if (isset($Call['Session']['Secondary']) && !empty($Call['Session']['Secondary']) && $Call['Session']['Secondary'] != -1)
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

    setFn('SID', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);
        return $Call['SID'];
    });

    setFn('Username', function ($Call)
    {
        if (PHP_SAPI == 'cli')
            return 0;

        $Call = F::Run(null, 'Audit', $Call);
        return isset($Call['Session']['User']['ID'])? $Call['Session']['User']['ID']: null;
    });

    setFn('Write', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);

        return F::Run('Entity', 'Update',
             [
                  'Entity' => 'Session',
                  'Where' => $Call['SID'],
                  'Data' => $Call['Data']
             ])['Current'];
    });

    setFn('Read', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);
        return isset($Call['Session'][$Call['Key']])? $Call['Session'][$Call['Key']]: null;
    });