<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Make', function ($Call)
    {
        // FIXME Templatize
        // GA FIXME Options

        if (isset($Call['DNT Support']) && F::Run('System.Interface.Web.DNT', 'Detect', $Call))
            $Code = '<!-- Do Not Track enabled. Google Analytics supressed. -->';
        else
            $Code = "<script type=\"text/javascript\">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-".$Call['ID']."']);
  _gaq.push(['_trackPageview']);
</script>
<js>ganalytics</js>";

        return $Code;
     });