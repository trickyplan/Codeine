<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Media Tag
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 23.11.10
     * @time 20:39
     */

    self::Fn('Process', function ($Call)
    {
        if (preg_match_all('@<media>(.*)</media>@SsUu',$Call['Input'], $Pockets))
        {
            foreach($Pockets[1] as $IX => $Match)
            {
                if (mb_substr($Match, mb_strlen($Match)-4) == '.css')
                {
                    if (mb_strpos($Match, '::'))
                    {
                        list ($Point, $ID) = explode('::', $Match);
                        $CSSs[] = $Point.'/'.$ID;
                    }
                }

                if (mb_substr($Match, mb_strlen($Match)-3) == '.js')
                {
                    if (mb_strpos($Match, '::'))
                    {
                        list ($Point, $ID) = explode('::', $Match);

                        $JSs[] = $Point.'/'.$ID;
                    }
                }
            }

            $CSSOut = '';
            foreach ($CSSs as $cCSS)
                $CSSOut .= '<link href="/'.$cCSS.'" rel="stylesheet" />';

            $Call['Input'] =
                    str_replace(
                        '<mediacss/>',
                        $CSSOut,
                        $Call['Input']);

           
            $Call['Input'] = str_replace($Pockets[0], '', $Call['Input']);
        }

        return $Call['Input'];
    });
