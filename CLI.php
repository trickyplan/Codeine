<?php

    /**
     * @author BreathLess
     * @type ${Type}
     * @description: ${Description}
     * @package ${Package}
     * @subpackage ${Subpackage}
     * @version ${Version}
     * @date 28.03.11
     * @time 2:24
     */
    try
    {
        defined('Root') || define('Root', __DIR__);

        include 'Library/Core/Core.php';

        echo Code::Run($argv[1]);
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
        Code::On('Front:Exception',
                 array('Message',$e->getMessage()));
    }
