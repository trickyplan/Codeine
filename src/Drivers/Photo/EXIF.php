<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2020.x.x
     */

    setFn('Decode', function ($Call) {
        return exif_read_data(F::Dot($Call, 'Data.Filename'));
    });
