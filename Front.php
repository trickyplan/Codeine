<?php

    try
    {
        defined('Root') || define('Root', __DIR__);

        include 'Core.php';
        
        Code::Run(array('F' => 'Code/Patterns/Front/Run'));

    }
    catch (Exception $e)
    {
        Code::On('Front','Exception',
                 array('Message',$e->getMessage()));
    }