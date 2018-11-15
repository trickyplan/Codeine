<?php

    setFn('beforeRTBRequest', function ($Call) {
        $Site = F::Dot($Call, 'AdRam.Site');
        $Categories = F::Dot($Site, 'Prohibited.Advertisement.Category');
        $IDs = F::Run('AdRam.Mixins.Category', 'Get Keys', F::Dot($Call, 'Category.Values', $Categories));
        $Call = F::Dot($Call, 'RTB.Request.bcat', $IDs);
        return $Call;
    });