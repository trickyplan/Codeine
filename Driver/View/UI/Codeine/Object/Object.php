<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Object Templater Codeine
     * @package Codeine
     * @subpackage Drivers
     * @version 
     * @date 18.11.10
     * @time 5:46
     */

    self::Fn('Make', function ($Call)
    {
        $Output = Data::Read('Layout::Objects/'.$Call['Item']['Entity'].'/'.$Call['Item']['Entity'].'_'.$Call['Item']['Plugin']);

        foreach ($Call['Contract']['Fusers'] as $Fuser)
            $Output = Code::Run(Code::Current(array(
                           'N' => 'View.Fusers',
                           'F' => 'Fusion',
                           'D' => $Fuser,
                           'Body' => $Output,
                           'Data' => $Call['Item']['Data'][$Call['Item']['ID']])
                      ));
        
        return $Output;
    });
