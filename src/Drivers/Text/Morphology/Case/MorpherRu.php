<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Convert', function ($Call)
    {
        $CachedResult = F::Run('IO', 'Read', [
            'Storage' => 'Morpher Ru Cache',
            'Where'   =>
            [
                'ID' => sha1($Call['Value'])
            ]
        ]);

        if ($CachedResult)
            $WebResult = array_pop($CachedResult);
        else
        {
            $WebResult = F::Run('IO', 'Read', [
                'Storage' => 'Web',
                'Where'   => 'http://api.morpher.ru/WebService.asmx/GetXml',
                'Format'  => 'Formats.XML',
                'Data'    =>
                    [
                        's' => $Call['Value']
                    ]
            ]);

            F::Run('IO', 'Write',
            [
                'Storage' => 'Morpher Ru Cache',
                'Where'   =>
                [
                    'ID' => sha1($Call['Value'])
                ],
                'Data'    => $WebResult
            ]);
        }

        $WebResult = array_pop($WebResult);
        $Result = $Call['Value'];

        switch ($Call['Case'])
        {
            case 'Nominativus':

            break;
            case 'Genitivus':
                if (isset($WebResult['Р']))
                    $Result = $WebResult['Р'];
            break;
            case 'Dativus':
                if (isset($WebResult['Д']))
                    $Result = $WebResult['Д'];
            break;
            case 'Accusativus':
                if (isset($WebResult['В']))
                    $Result = $WebResult['В'];
            break;
            case 'Ablativus':
                if (isset($WebResult['Т']))
                    $Result = $WebResult['Т'];
            break;
            case 'Praepositionalis':
                if (isset($WebResult['П']))
                    $Result = $WebResult['П'];
            break;

            default:
                $Result = $Call['Value'];
            break;
        }

        return $Result;
    });