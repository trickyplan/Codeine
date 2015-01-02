<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Universal', function ($Call)
    {
        // FIXME Templatize
        // GA FIXME Options
        $Code = '';
        $Dimension = '';

        if (isset($Call['Analytics']['Google']['DNT Support']) && F::Run('System.Interface.HTTP.DNT', 'Detect', $Call))
            $Code = '<!-- Do Not Track enabled. Google Analytics supressed. -->';
        else
        {
            if (in_array($Call['HTTP']['URL'], $Call['Analytics']['Google']['URLs']['Disabled']))
                ;
            else
            {
                if (defined('Overlay'))
                    $Dimension.= "ga('set', 'dimension1', '".Overlay."');";

                if (F::Environment() == 'Production')
                    $Code = "<script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
          ga('create', 'UA-".$Call['ID']."', '".$Call['HTTP']['Host']."');
          ga('require', 'linkid', 'linkid.js');
          ga('require', 'displayfeatures');
          ".$Dimension."
          ga('send', 'pageview');
        </script>";
            }
        }

        return $Code;
    });