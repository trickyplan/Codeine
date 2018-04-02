<?php

setFn('Do', function ($Call) {
    return F::Dot($Call, 'RTB.DSP.Items', array_map(function ($DSP) {
        $DSP['Request'] = j($DSP['Request']);
        return $DSP;
    }, F::Dot($Call, 'RTB.DSP.Items')));
});