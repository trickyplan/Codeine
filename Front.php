<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Index
     * @description: Front Controller
     * @package Codeine
     * @subpackage Core
     * @version 5.0
     * @date 01.12.10
     * @time 14:39
     */
        
    try
    {
        $ST = microtime(true);
        defined('Root') || define('Root', __DIR__);

        include 'Library/Core/Core.php';
        
        Code::Run(array('N' => 'Code.Flow.Front', 'F'=>'Run'));

        echo round((microtime(true)-$ST)*1000,2).'<br/>'.round(memory_get_usage(true)/1024);
        
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
        Code::On('Front:Exception',
                 array('Message',$e->getMessage()));
    }
