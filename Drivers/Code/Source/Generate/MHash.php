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

    self::Fn('Do', function ($Call)
    {
        $AlgosCount = mhash_count();

        for($a = 0; $a <$AlgosCount; $a++)
        {
            $AlgoName = mhash_get_hash_name($a);

            $Functions = array();

            $Functions['Get'] = 'if (isset($Call[\'Key\']))
            return mhash(MHASH_'.$AlgoName.', $Call[\'Input\'], $Call[\'Key\']);
        else
            return mhash(MHASH_'.$AlgoName.', $Call[\'Input\']);';
  // TODO DATA Write
            file_put_contents(
                Codeine.'/Drivers/Security/Hash/MHash/'.$AlgoName.'.php',
                F::Run(array(
                           '_N' => 'Code.Source.Generate.Driver',
                           '_F' => 'Do',
                           'Description'=> $AlgoName.' MHash Wrapper',
                           'Version' => '5.0',
                           'Functions' => $Functions
                      )));
        }
    });
