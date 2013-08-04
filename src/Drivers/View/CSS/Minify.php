<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Process', function ($Call)
    {
        if ($Call['CSS']['Strip Non-visible'])
            $Call['CSS']['Styles'] = str_replace (array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $Call['CSS']['Styles']);

        if ($Call['CSS']['Strip Comments'])
            $Call['CSS']['Styles'] = preg_replace ('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $Call['CSS']['Styles']);

        if ($Call['CSS']['Preserve Hacks'])
        {
            $Call['CSS']['Styles'] = preg_replace ('@>/\\*\\s*\\*/@', '>/*keep*/', $Call['CSS']['Styles']);
            $Call['CSS']['Styles'] = preg_replace ('@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $Call['CSS']['Styles']);
            $Call['CSS']['Styles'] = preg_replace ('@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $Call['CSS']['Styles']);
        }

        if ($Call['CSS']['Remove Whitespace'])
        {
            $Call['CSS']['Styles'] = preg_replace ('/\\s*{\\s*/', '{', $Call['CSS']['Styles']);
            $Call['CSS']['Styles'] = preg_replace ('/;?\\s*}\\s*/', '}', $Call['CSS']['Styles']);
            $Call['CSS']['Styles'] = preg_replace ('/\\s*;\\s*/', ';', $Call['CSS']['Styles']);
            $Call['CSS']['Styles'] = preg_replace ('/url\\(\\s*([^\\)]+?)\\s*\\)/x', 'url($1)', $Call['CSS']['Styles']);
            $Call['CSS']['Styles'] = preg_replace ('/\\s*([{;])\\s*([\\*_]?[\\w\\-]+)\\s*:\\s*(\\b|[#\'"])/x', '$1$2:$3', $Call['CSS']['Styles']);
            $Call['CSS']['Styles'] = preg_replace ('/[ \\t]*\\n+\\s*/', "\n", $Call['CSS']['Styles']);
        }

        if ($Call['CSS']['Minimize Colors'])
            $Call['CSS']['Styles'] = preg_replace ('/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i', '$1#$2$3$4$5', $Call['CSS']['Styles']);

        return $Call;
     });