<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Length', function ($Call)
    {
        if (isset($Call['View']['HTML']['Keywords']) && is_string($Call['View']['HTML']['Keywords']))
        {
            $Length = mb_strlen($Call['View']['HTML']['Keywords']);

            if ($Length > $Call['SEO']['Audit']['Keywords']['Length']['Maximum'])
            {
                $Call = F::Hook('SEO.Audit.Keywords.TooLong', $Call);
                F::Log('SEO Keywords is too long *'.$Length.' chars* over *'
                    .$Call['SEO']['Audit']['Keywords']['Length']['Maximum'].'*', LOG_WARNING, 'Marketing');
            }
            elseif ($Length < $Call['SEO']['Audit']['Keywords']['Length']['Minimum'])
            {
                $Call = F::Hook('SEO.Audit.Keywords.TooShort', $Call);
                F::Log('SEO Keywords is too short *'.$Length.' chars* over *'
                    .$Call['SEO']['Audit']['Keywords']['Length']['Minimum'].'*', LOG_WARNING, 'Marketing');
            }
            else
            {
                F::Log('SEO Keywords length is optimal ', LOG_GOOD, 'Marketing');
            }
        }
        
        return $Call;
    });