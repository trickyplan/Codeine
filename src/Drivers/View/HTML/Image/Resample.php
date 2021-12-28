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
                    $Call['Current Image']['Height'] = 0;

                if (isset($Call['Current Image']['Width']))
                    ;
                else
                    $Call['Current Image']['Width'] = 0;

                $Imagick->resizeImage($Call['Current Image']['Width'], $Call['Current Image']['Height'], Imagick::FILTER_BESSEL, 0.5, true);

                $Call['Current Image']['Data'] = $Imagick->getImageBlob();
            }
            catch (Exception $e)
            {
                F::Log($e->getMessage(), LOG_ERR);
            }

        }

        return $Call;
    });