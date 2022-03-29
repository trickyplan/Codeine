<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2019.x
     */

    setFn('Read', function ($Call) {
        if (($Dictionary = F::Dot($Call, 'Compress.ZSTD.Dictionary')) === null) {
            $Result = zstd_uncompress($Call['Data']);
        } else {
            $Result = zstd_uncompress_dict($Call['Data'], $Dictionary);
        }

        return $Result;
    });

    setFn('Write', function ($Call) {
        if (($Dictionary = F::Dot($Call, 'Compress.ZSTD.Dictionary')) === null) {
            $Result = zstd_compress($Call['Data'], F::Dot($Call, 'Compress.ZSTD.Level'));
        } else {
            $Result = zstd_compress_dict($Call['Data'], $Dictionary);
        }

        return $Result;
    });