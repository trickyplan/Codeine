<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        if (isset($Call['Current Image']['Resample']) && !empty($Call['Current Image']['Data'])) {
            F::Log('Resample is incorrect name and will be *deprecated*, please use Resize', LOG_WARNING);
            try {
                $Imagick = new Imagick();
                $Imagick->readimageblob($Call['Current Image']['Data']);
                $OldWidth = $Imagick->getImageWidth();
                $OldHeight = $Imagick->getImageHeight();

                if (isset($Call['Current Image']['Height'])) {
                    ;
                } else {
                    $Call['Current Image']['Height'] = round(
                        $OldHeight * ($Call['Current Image']['Width'] / $OldWidth)
                    );
                }

                if (isset($Call['Current Image']['Width'])) {
                    ;
                } else {
                    $Call['Current Image']['Width'] = round(
                        $OldWidth * ($Call['Current Image']['Height'] / $OldHeight)
                    );
                }

                if ($Result = $Imagick->resizeImage(
                    $Call['Current Image']['Width'],
                    $Call['Current Image']['Height'],
                    Imagick::FILTER_BESSEL,
                    0.5
                )) {
                    ;
                } else {
                    F::Log('Imagemagick: ' . $Result, LOG_ERR);
                }

                $Call['Current Image']['Data'] = $Imagick->getImageBlob();
            } catch (Exception $e) {
                F::Log($e->getMessage(), LOG_ERR);
            }
        }

        return $Call;
    });