<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Make', function ($Call)
    {
        // FIXME Templatize

        $Call['Options']['id'] = $Call['ID'];

        if (isset($Call['DNT Support']) && F::Run('System.Interface.Web.DNT', 'Detect', $Call))
            $Code = '<!-- Do Not Track enabled. Yandex Metrics supressed. -->';
        else
            $Code = '
            <!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter13852573 = new Ya.Metrika('.json_encode($Call['Options']).');
        } catch(e) {}
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/13852573" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
    ';

        return $Code;
     });