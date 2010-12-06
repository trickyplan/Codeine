<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: MCrypt Codeine Driver Generator 
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 3:27
     */

    self::Fn('Generate', function ($Call)
    {
        $Algos = hash_algos();

        foreach ($Algos as $Algo)
        {
            $Functions = array();

            $Functions['Get'] = 'if (isset($Call[\'Key\']))
            return hash(\''.$Algo.'\', $Call[\'Input\'], $Call[\'Key\']);
        else
            return hash(\''.$Algo.'\', $Call[\'Input\']);';


            // TODO DATA Write
            file_put_contents(
                Engine.Data::Path('Code').'Security/Hash/PHP/'.str_replace(',','.',$Algo).'.php', Code::Run(array(
                           'N' => 'Code.Source.Driver',
                           'F' => 'Generate',
                           'Description'=> $Algo.' Hash Extension Wrapper',
                           'Version' => '5.0',
                           'Functions' => $Functions
                      )));
        }
    });
