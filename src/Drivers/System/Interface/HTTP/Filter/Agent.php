<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        foreach ($Call['HTTP']['Filter']['Agent']['Rules'] as $FilterName => $Filter)
            foreach ($Filter as $Match)
                if (preg_match('/'.$Match.'/Ssu', $Call['HTTP']['Agent']))
                {
                    F::Log('HTTP Agent Filter *'.$FilterName.'* matched', LOG_WARNING, 'Security');
                    return false;
                }
        return true;
    });