<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */


    self::setFn('Do', function ($Call)
    {
        return F::Run('Entity.List', 'Do', array('Entity' => 'Session', 'Template' => 'Control', 'PageURL' => '/control/Session/page', 'Context' => 'app'), $Call);
    });

    self::setFn('Show', function ($Call)
    {
        return F::Run('Entity.Show.Static', 'Do', array('Entity' => 'Session', 'PageURL' => '/control/Session/page', 'Context' => 'app'), $Call);
     });

    self::setFn('Delete', function ($Call)
    {
        return F::Run('Entity', 'Delete', array('Entity' => 'Session'), $Call);
    });

    self::setFn('Flush', function ($Call)
    {
        return F::Run('Entity.Delete', 'Do', array('Entity' => 'Session'), $Call);
    });