<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
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
  _gaq.push(['_trackPageLoadTime']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>";

        return $Code;
     });

    setFn('Universal', function ($Call)
    {
        // FIXME Templatize
        // GA FIXME Options
        if (isset($Call['DNT Support']) && F::Run('System.Interface.Web.DNT', 'Detect', $Call))
            $Code = '<!-- Do Not Track enabled. Google Analytics supressed. -->';
        else
            $Code = "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-".$Call['ID']."', '".$Call['HTTP']['Host']."');
  ga('send', 'pageview');
</script>";

        return $Code;
    });