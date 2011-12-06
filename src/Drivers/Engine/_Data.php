<?php

    /* Codeine
     * @author BreathLess
     * @description: Data Engine
     * @package Codeine
     * @version 6.0
     * @date 30.07.11
     * @time 18:09
     */

    self::setFn('Open', function ($Call)
    {
        $Link = F::Run(
                    $Call['Storages'][$Call['Storage']],
                    array(
                        '_N' => $Call['Storages'][$Call['Storage']]['Method'],
                        '_F' => 'Open',
                        'NoBehaviours' => true));

        if (empty($Link) && isset($Call['Storages'][$Call['Storage']]['Essential']))
            die('Essential storage failed');

        return F::Set('Storages.'.$Call['Storage'], $Link);
    });

    self::setFn('Close', function ($Call)
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

    self::setFn('AutoOpen', function ($Call)
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

    self::setFn('Operation', function ($Call)
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

    self::setFn('Load', function ($Call)
    {
        $Data = F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));

        return $Data;
    });

    self::setFn ('Values', function ($Call)
    {
        return F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));
    });

    self::setFn('Find', function ($Call)
    {
        return F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));
    });

    self::setFn('Create', function ($Call)
    {
        return F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));
    });

    self::setFn('Update', function ($Call)
    {
        return F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));
    });

    self::setFn('Delete', function ($Call)
    {
        return F::Run($Call, array('_F' => 'Operation', 'Operation' => $Call['_F']));
    });
