<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 2019.x
     */

    setFn('Read', function ($Call)
    {
        if (isset($Call['Compress']['Driver']))
            $Result = F::Run('Data.Compress.'.$Call['Compress']['Driver'], null, $Call);
        else
        {
            $Live = F::Dot($Call, 'Compress.Modes.'.$Call['Compress']['Mode']);
            $Live['Method'] = 'Read';

            if ($Live)
                $Result = F::Live($Live, $Call);
            else
                $Result = null;
        }

        return $Result;
    });

    setFn('Write', function ($Call)
    {
        $OriginalSize = mb_strlen($Call['Data']);

        if (isset($Call['Compress']['Driver']))
            $Result = F::Run('Data.Compress.'.$Call['Compress']['Driver'], null, $Call);
        else
        {
            $Live = F::Dot($Call, 'Compress.Modes.'.$Call['Compress']['Mode']);
            $Live['Method'] = 'Write';

            if ($Live)
                $Result = F::Live($Live, $Call);
            else
                $Result = null;
        }

        if ($Result !== null and F::Dot($Call, 'Compress.Ratio.Analyze'))
        {
            $CompressedSize = mb_strlen($Result);
            $Ratio = round((1 - $CompressedSize / $OriginalSize)*100, 2);
            if ($Ratio < F::Dot($Call, 'Compress.Ratio.Min'))
            {
                F::Log('Inefficient compression: '.$Ratio.'%', LOG_NOTICE);
                F::Log((isset($Call['Compress']['Driver'])? 'Driver is *'.$Call['Compress']['Driver'].'*': 'Mode is *'.$Call['Compress']['Mode']).'*', LOG_NOTICE);
                F::Log('Data is *'.mb_substr($Call['Data'], 0, 16).'…*', LOG_INFO);
            }
            else
                F::Log('Efficient compression: '.$Ratio.'%', LOG_INFO);
        }

        return $Result;
    });