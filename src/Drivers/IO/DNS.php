<?php

    setFn('Exists', function ($Call) {
        return checkdnsrr(F::Dot($Call, 'Where.ID'), 'A');
    });

    setFn('Read', function ($Call) {
        return gethostbyname(F::Dot($Call, 'Where.ID'));
    });

    setFn('Write', function ($Call) {
        return null;
    });