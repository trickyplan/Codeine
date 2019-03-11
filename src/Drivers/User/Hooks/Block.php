<?php

    setFn('ByEmail', function ($Call) {
        $Email = F::Live(F::Dot($Call, 'Email'), $Call);
    });