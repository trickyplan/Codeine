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
            $Call = F::Run(null, 'Register', $Call);
        else
        {
            $Call['Session'] =
                F::Run('Entity', 'Read',
                    array(
                         'Entity' => 'Session',
                         'Where' => $Call['SID']
                    ));

            if (isset($Call['Session']['User']))
            {
                if($Call['Session']['Expire'] < time())
                    $Call = F::Run(null, 'Annulate', $Call);
                else
                {
                    $User = F::Run('Entity', 'Read',
                            array(
                                 'Entity' => 'User',
                                 'Where' => $Call['Session']['User']
                            ));

                    if ($User[0]['Status'] == -1)
                        $Call = F::Run(null, 'Annulate', $Call);

                    $Call['Session']['User'] = $User[0];
                }
            }
        }

        return $Call;
    });

    self::setFn('Register', function ($Call)
    {
        $Call['SID'] = F::Live($Call['Generator']);

        F::Run($Call['Source'], 'Write', $Call);

        $Call['Session'] = F::Run('Entity', 'Create',
            array(
                 'Entity' => 'Session',
                 'Where' => $Call['SID'],
                 'Data' =>
                    array (
                        'User' => null,
                        'Created' => time()
                    )
            ));

        return $Call;
    });

    self::setFn('Annulate', function ($Call)
    {
        F::Run($Call['Source'], 'Annulate', $Call);

        F::Run('Entity', 'Delete',
                        array(
                             'Entity' => 'Session',
                             'Where' => $Call['SID']
                        ));

        return $Call;
    });

    self::setFn('Attach', function ($Call)
    {
        return F::Run('Entity', 'Update',
             array(
                  'Entity' => 'Session',
                  'Where' => $Call['SID'],
                  'Data' => array('User' => $Call['User'], 'Expire' => time()+ $Call['TTL'])
             ));
    });

    self::setFn('Detach', function ($Call)
    {
        $Call = F::Run(null, 'Audit', $Call);
        return F::Run('Entity', 'Update',
             array(
                  'Entity' => 'Session',
                  'Where' => $Call['SID'],
                  'Data' => array('User' => null)
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