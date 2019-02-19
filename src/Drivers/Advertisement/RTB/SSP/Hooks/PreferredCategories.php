<?php

setFn('beforeRTBRequest', function ($Call) {
    $Site = F::Dot($Call, 'AdRam.Site');
    $Categories = F::Dot($Site, 'Advertisement.Category');

    if ($Categories) {
        $IDs = F::Run('AdRam.Mixins.Category', 'Get Keys', F::Dot($Call, 'Category.Values', $Categories));
        $Call = F::Dot($Call, 'RTB.Request.cat', $IDs);
    }
    return $Call;
});