<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 6:22
     */

    self::Fn('Do', function ($Call)
    {
        chdir(Codeine);
        $Version = shell_exec('cat VERSION');
        
        $Drivers = F::Run(
            array(
                '_N' => 'Code.Source.Enumerate.Driver',
                '_F' => 'ListAll'
            )
        );

        $Contracts = F::Run(
            array(
                '_N' => 'Code.Source.Enumerate.Contract',
                '_F' => 'ListAll'
            )
        );

        $Output = array('Content' => array());

        $Output['Content'][] = array('UI' => 'Text.Heading', 'Value' => 'Статус системы');
        $Output['Content'][] = array('UI' => 'Block.VTable', 'Value' =>
            array(
                'Версия' => $Version,
                'Драйверов' => count($Drivers),
                'Контрактов' => count($Contracts)
            ));

         var_dump(F::Run(array(
                   '_N' => 'Engine.Data',
                   '_F' => 'Read',
                   'Point' => 'Page',
                   'ID' => F::Run(
                            array(
                                  '_N' => 'Engine.Data',
                                  '_F' => 'Find',
                                  'Point' => 'Page',
                                  'Where' =>
                                  array(
                                      'Rating' =>
                                          array(
                                              '>' => 39
                                          )
                                      )
                              ))
                     )));
        return $Output;
    });
