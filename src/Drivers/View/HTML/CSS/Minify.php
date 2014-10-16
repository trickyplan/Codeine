<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Do', function ($Call)
    {
        if (isset($Call['CSS']['Styles']))
            foreach ($Call['CSS']['Styles'] as $Style)
            {
                if ($Call['CSS']['Strip Non-visible'])
                    $Style = str_replace (["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $Style);

                if ($Call['CSS']['Strip Comments'])
                    $Style = preg_replace ('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $Style);

                if ($Call['CSS']['Preserve Hacks'])
                {
                    $Style = preg_replace ('@>/\\*\\s*\\*/@', '>/*keep*/', $Style);
                    $Style = preg_replace ('@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $Style);
                    $Style = preg_replace ('@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $Style);
                }

                if ($Call['CSS']['Remove Whitespace'])
                {
                    $Style = preg_replace ('/\\s*{\\s*/', '{', $Style);
                    $Style = preg_replace ('/;?\\s*}\\s*/', '}', $Style);
                    $Style = preg_replace ('/\\s*;\\s*/', ';', $Style);
                    $Style = preg_replace ('/url\\(\\s*([^\\)]+?)\\s*\\)/x', 'url($1)', $Style);
                    $Style = preg_replace ('/\\s*([{;])\\s*([\\*_]?[\\w\\-]+)\\s*:\\s*(\\b|[#\'"])/x', '$1$2:$3', $Style);
                    $Style = preg_replace ('/[ \\t]*\\n+\\s*/', "\n", $Style);
                }

                if ($Call['CSS']['Minimize Colors'])
                    $Style = preg_replace ('/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i', '$1#$2$3$4$5', $Style);
            }

        return $Call;
     });