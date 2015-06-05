<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Current Image']['Resample']) && !empty($Call['Current Image']['Data']))
        {
            try
            {
                $GImage = new Gmagick();
                $GImage->readimageblob($Call['Current Image']['Data']);

                if (isset($Call['Current Image']['Height']))
                    ;
                else
                    $Call['Current Image']['Height'] =
                        ceil(($Call['Current Image']['Width']/$GImage->getimagewidth())*$GImage->getimageheight());

                if (isset($Call['Current Image']['Width']))
                    ;
                else
                    $Call['Current Image']['Width'] =
                        ceil(($Call['Current Image']['Height']/$GImage->getimageheight())*$GImage->getimagewidth());

                $GImage->resizeImage($Call['Current Image']['Width'], $Call['Current Image']['Height'], Gmagick::FILTER_LANCZOS, 1);

                $Call['Current Image']['Data'] = $GImage->getImageBlob();
            }
            catch (Exception $e)
            {
                F::Log($e->getMessage(), LOG_ERR);
            }

        }

        return $Call;
    });