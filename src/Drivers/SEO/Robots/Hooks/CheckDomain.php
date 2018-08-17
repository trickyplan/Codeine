<?php

setFn('Do', function ($Call) {
    $Proxy = F::Run('Entity', 'Read',
        [
            'Entity' => 'AdRam.Proxy',
            'Where'  =>
                [
                    'Host' => $Call['HTTP']['Domain']
                ]
        ]);
    return F::Dot($Call, 'SEO.Robots.Domain Is Valid', empty($Proxy));
});