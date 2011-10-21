<?php

    /* Codeine
     * @author BreathLess
     * @description: Data Engine
     * @package Codeine
     * @version 6.0
     * @date 30.07.11
     * @time 18:09
     */

    self::Fn('Open', function ($Call)
    {
        return F::Set('Storages.'.$Call['Storage'],
                    F::Run(
                        $Call['Storages'][$Call['Storage']],
                        array(
                            '_N' => $Call['Storages'][$Call['Storage']]['Method'],
                            '_F' => 'Open',
                            'NoBehaviours' => true)));
    });

    self::Fn('Close', function ($Call)
    {
        return F::Run(
            $Call['Storages'][$Call['Storage']],
                               array(
                                   '_N' => $Call['Storages'][$Call['Storage']]['Method'],
                                   '_F' => 'Close',
                                   'Link' => F::Get('Storages.'.$Call['Storage']),
                                   'NoBehaviours' => true)
                           );
    });

    self::Fn('AutoOpen', function ($Call)
    {
        if (isset($Call['Storages'][$Call['Storage']]))
        {
            if (null === ($Call['Link'] = F::Get('Storages.'.$Call['Storage'])))
                $Call['Link'] = F::Run(
                                    $Call,
                                    array('_F' => 'Open', 'NoBehaviours' => true));
        }

        return $Call;
    });

    self::Fn('Operation', function ($Call)
    {
        $Operation = $Call['Operation'];

        $Call = F::Merge($Call['Storages'][$Call['Storage']], $Call);

        $Call = F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=>'before'.$Call['Operation']));

        $Result = F::Run(
                        $Call,
                        array(
                             '_N' => $Call['Method'],
                             '_F' => $Operation,
                            'NoBehaviours' => true
                        ));

        $Call = F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=>'after'.$Call['Operation']));

        return $Result;
    });

    self::Fn('Load', function ($Call)
    {
        $Data = F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));

        return $Data;
    });

    self::Fn('Find', function ($Call)
    {
        return F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));
    });

    self::Fn('Create', function ($Call)
    {
        return F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));
    });

    self::Fn('Update', function ($Call)
    {
        return F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));
    });

    self::Fn('Delete', function ($Call)
    {
        return F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));
    });
