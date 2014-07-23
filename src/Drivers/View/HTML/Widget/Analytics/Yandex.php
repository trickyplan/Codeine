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

        $Call['Options']['id'] = $Call['ID'];
        $Code = '';

        if (isset($Call['DNT Support']) && F::Run('System.Interface.HTTP.DNT', 'Detect', $Call))
            $Code = '<!-- Do Not Track enabled. Yandex Metrics supressed. -->';
        else
        {
            if ((F::Environment() == 'Production'))
            {
                $Code = '<!-- Yandex.Metrika counter -->
<script>
(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter'.$Call['Options']['id'].' = new Ya.Metrika({id:'.json_encode($Call['Options']).', webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");
</script>
<!-- /Yandex.Metrika counter -->';
            }
        }

        return $Code;
     });