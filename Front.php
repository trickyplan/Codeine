<?php

    include 'Core.php';

    try
    {
        defined('Root') || define('Root', __DIR__);

        Code::On('Front', 'beforeStart');

        Code::Run(array('F' => 'Code/Patterns/Front/Run'));

        Code::On('Front', 'afterStart');
    }
    catch (Exception $e)
    {
        // FIXME Error.json
        echo $e->getMessage();
    }