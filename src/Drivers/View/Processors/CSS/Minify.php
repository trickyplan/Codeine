<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Process', function ($Call)
    {
        $CSS  = $Call['Value'];

        if ($Call['Strip Non-visible'])
            $CSS = str_replace (array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $CSS);

        if ($Call['Strip Comments'])
            $CSS = preg_replace ('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $CSS);

        if ($Call['Preserve Hacks'])
        {
            $CSS = preg_replace ('@>/\\*\\s*\\*/@', '>/*keep*/', $CSS);
            $CSS = preg_replace ('@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $CSS);
            $CSS = preg_replace ('@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $CSS);
        }

        if ($Call['Remove Whitespace'])
        {
            $CSS = preg_replace ('/\\s*{\\s*/', '{', $CSS);
            $CSS = preg_replace ('/;?\\s*}\\s*/', '}', $CSS);
            $CSS = preg_replace ('/\\s*;\\s*/', ';', $CSS);
            $CSS = preg_replace ('/url\\(\\s*([^\\)]+?)\\s*\\)/x', 'url($1)', $CSS);
            $CSS = preg_replace ('/\\s*([{;])\\s*([\\*_]?[\\w\\-]+)\\s*:\\s*(\\b|[#\'"])/x', '$1$2:$3', $CSS);
            $CSS = preg_replace ('/[ \\t]*\\n+\\s*/', "\n", $CSS);
        }

        if ($Call['Minimize Colors'])
            $CSS = preg_replace ('/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i', '$1#$2$3$4$5', $CSS);

        return $CSS;
     });