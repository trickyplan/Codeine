<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 03.04.11
     * @time 1:51
     */

    self::Fn('Do', function ($Call)
    {
        $Drivers = Code::Run(array('N' => 'Code.Source.Enumerate', 'F' => 'Drivers'));
        $Contracts = Code::Run(array('N' => 'Code.Source.Enumerate', 'F' => 'Contracts'));
        $Covered = (array_intersect($Drivers, $Contracts));
        $NotCovered = (array_diff($Drivers, $Contracts));
        
        echo 'Драйверов: '.count($Drivers).'<br/>';
        echo 'Контрактов: '.count($Contracts).'<br/>';
        echo 'Покрыто: '.count($Covered).' ('.round((count($Covered)/count($Drivers))*100).'%)<br/>';
        echo 'Непокрыто: '.count($NotCovered).' ('.round((count($NotCovered)/count($Drivers))*100).'%)<br/>';
        
        return ;
    });
