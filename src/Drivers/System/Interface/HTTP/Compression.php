<?php

/* Codeine
 * @author bergstein@trickyplan.com
 * @description  
 * @package Codeine
 * @version 2019.x
 */

    setFn('Do', function ($Call)
    {
        if (F::Dot($Call, 'System.Interface.HTTP.Compression.Enabled'))
        {
            if (isset($Call['HTTP']['Request']['Headers']['Accept-Encoding']))
            {
                $Call['Output'] = ob_get_clean().$Call['Output'];
                $ContentType = F::Dot($Call, 'HTTP.Headers.Content-Type:');
                if (($Pos = str_contains($ContentType, ';')))
                    $ContentType = F::Dot($Call, 'HTTP.Headers.Content-Type:');
                else
                    $ContentType = mb_substr($ContentType, 0, $Pos);

                if (in_array($ContentType, F::Dot($Call, 'System.Interface.HTTP.Compression.Content-Types.Allowed')))
                {
                    $Algorithm = null;
                    $AcceptEncoding = $Call['HTTP']['Request']['Headers']['Accept-Encoding'];
                    $AcceptEncodings = explode(',', $AcceptEncoding);

                    foreach ($AcceptEncodings as &$Encoding)
                        $Encoding = trim($Encoding);

                    $AllowedEncodings = F::Dot($Call, 'System.Interface.HTTP.Compression.Algorithms.Allowed');

                    if (F::Dot($Call, 'System.Interface.HTTP.Compression.Algorithms.PreferClientOrder'))
                        $Algorithms = array_intersect($AcceptEncodings, $AllowedEncodings);
                    else
                        $Algorithms = array_intersect($AllowedEncodings, $AcceptEncodings);

                    if (empty($Algorithms))
                        F::Log('No compatible compression algorithms', LOG_INFO);
                    else
                        $Call = F::Run('System.Interface.HTTP.Compression.'.array_shift($Algorithms), 'Do', $Call);
                }
                else
                    F::Log('Compression is not allowed by Content-Type', LOG_INFO);
            }
            else
                F::Log('Compression is not allowed, Accept-Encoding is not defined', LOG_INFO);
        }
        else
            F::Log('Compression is *disabled*', LOG_INFO);

        return $Call;
    });