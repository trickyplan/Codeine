<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Write', function ($Call)
    {
        F::Run('IO', 'Write', array(
                                    'Storage' => $Call['Storage'],
                                    'Scope' => $Call['Entity'].'2'.$Call['Node'],
                                    'Where' => array ($Call['Entity'] . 'ID' => $Call['Data']['ID'])
                               ));

        foreach ($Call['Value'] as $cValue)
            F::Run('Code.Run.Delayed', 'Run',
                array(
                     'Run' => array(
                         'Service'  => 'IO',
                         'Method'   => 'Write',
                         'Call'     => array(
                                        'Storage' => $Call['Storage'],
                                        'Scope' => $Call['Entity'].'2'.$Call['Node'],
                                        'Data' => array (
                                            $Call['Entity'].'ID' => $Call['Data']['ID'],
                                            $Call['Node'].'ID'  => $cValue
                                        )
                                   )
                     )
                ));

        return null;
    });

    self::setFn('Widget', function ($Call)
    {
        return F::Merge(F::Merge($Call['Widgets'][$Call['Purpose']],
                        array(
                            'Value' => F::Run('Entity.Dict', 'Get',
                                array(// FIXME
                                     'Entity' => $Call['Node']['Link']['Entity'],
                                     'Key' => $Call['Node']['Link']['Key'])))),
                            array('Entity' => $Call['Node']['Link']['Entity'], 'Link' => $Call['Node']['Link']['Entity'],
                                  'Key' => $Call['Node']['Link']['Key']));
    });


    self::setFn('Read', function ($Call)
    {
        $Data = F::Run('IO', 'Read', array(
                                    'Storage' => $Call['Storage'],
                                    'Keys' => array ($Call['Node'] . 'ID'),
                                    'Scope' => $Call['Entity'].'2'.$Call['Node'],
                                    'Where' =>
                                        array (
                                            $Call['Entity'].'ID' => $Call['ID']
                                        )
                               ));

        $Result = array();

        foreach ($Data as $Row)
            $Result[] = $Row[$Call['Node'] . 'ID'];

        return $Result;
    });