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
                Engine.Data::Path('Code').'Security/Hash/MHash/'.$AlgoName.'.php', Code::Run(array(
                           'N' => 'Code.Source.Driver',
                           'F' => 'Generate',
                           'Description'=> $AlgoName.' MHash Wrapper',
                           'Version' => '5.0',
                           'Functions' => $Functions
                      )));
        }
    });
