<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Length', function ($Call)
    {
        $Length = mb_strlen($Call['View']['HTML']['Description']);

        if ($Length > $Call['SEO']['Audit']['Description']['Length']['Maximum'])
        {
            $Call = F::Hook('SEO.Audit.Description.TooLong', $Call);
            F::Log('SEO Description is too long *'.$Length.' chars* over *'
                .$Call['SEO']['Audit']['Description']['Length']['Maximum'].'*', LOG_WARNING, 'SEO');
        }
        elseif ($Length < $Call['SEO']['Audit']['Description']['Length']['Minimum'])
        {
            $Call = F::Hook('SEO.Audit.Description.TooShort', $Call);
            F::Log('SEO Description is too short *'.$Length.' chars* over *'
                .$Call['SEO']['Audit']['Description']['Length']['Minimum'].'*', LOG_WARNING, 'SEO');
        }
        else
        {
            F::Log('SEO Description length is optimal ', LOG_GOOD, 'SEO');
        }

        return $Call;
    });