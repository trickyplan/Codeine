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
        $Output = Data::Read('Layout::UI/Codeine/Object/'.$Call['Entity'].'/'.$Call['Style']);

        foreach ($Call['Contract']['Fusers'] as $Fuser)
            $Output = Code::Run(Code::Current(array(
                           'N' => 'View.UI.Codeine.Object.Fusers',
                           'F' => 'Fusion',
                           'D' => $Fuser,
                           'Body' => $Output,
                           'Data' => $Call['Data'])
                      ));
        
        return $Output;
    });
