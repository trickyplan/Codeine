<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        if (isset($Call['Current Image']['Thumb']) && !empty($Call['Current Image']['Data'])) {
            try {
                $Imagick = new Imagick();
                $Imagick->readimageblob($Call['Current Image']['Data']);
                /*            $GImage->setCompressionQuality($Call['Image']['Quality']);*/


                if (!isset($Call['Current Image']['Height'])) {
                    $Call['Current Image']['Height'] =
                        ceil(
                            ($Call['Current Image']['Width'] / $Imagick->getimagewidth()) * $Imagick->getimageheight()
                        );
                }

                if (!isset($Call['Current Image']['Width'])) {
                    $Call['Current Image']['Width'] =
                        ceil(
                            ($Call['Current Image']['Height'] / $Imagick->getimageheight()) * $Imagick->getimagewidth()
                        );
                }

                $Imagick->cropThumbnailImage($Call['Current Image']['Width'], $Call['Current Image']['Height']);

                $Call['Current Image']['Data'] = $Imagick->getImageBlob();
            } catch (Exception $e) {
                F::Log($e->getMessage(), LOG_ERR);
                F::Log($Call['Current Image'], LOG_ERR);
            }
        }

        return $Call;
    });