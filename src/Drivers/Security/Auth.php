<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
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
                $Call['Session'] = F::Run(null, 'Register', $Call);

            if (isset($Call['Session']['User']) && !empty($Call['Session']['User']))
            {
                if($Call['Session']['Expire'] < time())
                {
                    $Call = F::Run(null, 'Annulate', $Call);
                }
                else
                {
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
                 'Data' => array()
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

        return $Call;
    });

    self::setFn('Attach', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);

        return F::Run('Entity', 'Update',
             array(
                  'Entity' => 'Session',
                  'Where' => $Call['SID'],
                  'Data' =>
                        array(
                            'User' => $Call['User'],
                            'Expire' => time()+87600) // FIXME
             ));
    });

    self::setFn('Detach', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);

        return F::Run('Entity', 'Update',
             array(
                  'Entity' => 'Session',
                  'Where' => $Call['SID'],
                  'Data' =>
                  array(
                      'User' => -1
                  )
             ));
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