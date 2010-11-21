<?php

    include 'MicroCore.php';

    Core::Initialize();

    try
    {
        if (!defined('Root'))
            define('Root', __DIR__);

        $Call = Code::Run(array(
                               'F' => 'System/Interface/Input'
                          ));

        Code::Run(
            array('F'    => 'System/Output/Output',
                  'D' => 'HTTP',
                  'Output' => Code::Run(
                                array('F'    => 'View/Render/Render',
                                      'D' => 'Codeine',
                                      'Body' =>
                                            Code::Run($Call)))
                 ));
        
    }
    catch (Exception $e)
    {
        // FIXME Error.json
        echo $e->getMessage();
    }