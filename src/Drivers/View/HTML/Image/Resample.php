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
                $Imagick = new Imagick();
                $Imagick->readimageblob($Call['Current Image']['Data']);

                if (isset($Call['Current Image']['Height']))
                    ;
                else
                    $Call['Current Image']['Height'] =
                        ceil(($Call['Current Image']['Width']/$Imagick->getimagewidth())*$Imagick->getimageheight());

                if (isset($Call['Current Image']['Width']))
                    ;
                else
                    $Call['Current Image']['Width'] =
                        ceil(($Call['Current Image']['Height']/$Imagick->getimageheight())*$Imagick->getimagewidth());

                $Imagick->resizeImage($Call['Current Image']['Width'], $Call['Current Image']['Height'], Imagick::FILTER_LANCZOS, 1);

                $Call['Current Image']['Data'] = $Imagick->getImageBlob();
            }
            catch (Exception $e)
            {
                F::Log($e->getMessage(), LOG_ERR);
            }

        }

        return $Call;
    });