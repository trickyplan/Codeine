<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Current Image']['Thumb']) && !empty($Call['Current Image']['Data']))
        {
            try
            {
                $GImage = new Gmagick();
                $GImage->readimageblob($Call['Current Image']['Data']);
    /*            $GImage->setCompressionQuality($Call['Image']['Quality']);*/


                if (!isset($Call['Current Image']['Height']))
                    $Call['Current Image']['Height'] =
                        ceil(($Call['Current Image']['Width']/$GImage->getimagewidth())*$GImage->getimageheight());

                if (!isset($Call['Current Image']['Width']))
                    $Call['Current Image']['Width'] =
                        ceil(($Call['Current Image']['Height']/$GImage->getimageheight())*$GImage->getimagewidth());

                $GImage->cropthumbnailimage($Call['Current Image']['Width'], $Call['Current Image']['Height']);

                $Call['Current Image']['Data'] = $GImage->getImageBlob();
            }
            catch (Exception $e)
            {
                F::Log($e->getMessage(), LOG_ERR);
                F::Log($Call['Current Image'], LOG_ERR);
            }

        }

        return $Call;
    });