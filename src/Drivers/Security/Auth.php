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
                {
                    F::Run('Entity', 'Delete',
                        array(
                             'Entity' => 'Session',
                             'Where' => $Call['SID']
                        ));
                }
                else
                {
                    $User = F::Run('Entity', 'Read',
                            array(
                                 'Entity' => 'User',
                                 'Where' => $Call['Session']['User']
                            ));

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
        // TODO Realize "Annulate" function


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
        return F::Run('Entity', 'Update',
             array(
                  'Entity' => 'Session',
                  'Where' => $Call['SID'],
                  'Data' => $Call['Data'])
             );
    });