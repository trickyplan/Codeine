<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Make', function ($Call)
    {
        // FIXME Templatize
        // GA FIXME Options

        if (isset($Call['DNT Support']) && F::Run('System.Interface.Web.DNT', 'Detect', $Call))
            $Code = '<!-- Do Not Track enabled. Google Analytics supressed. -->';
        else
            $Code = "<script type=\"text/javascript\">var _gaq = [['_setAccount', '".$Call["ID"]."'],['_trackPageview']];(function (d, t){var g = d.createElement(t), s = d.getElementsByTagName(t)[0];g.src = ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js';s.parentNode.insertBefore(g, s)}(document, 'script'));</script>";

        return $Code;
     });