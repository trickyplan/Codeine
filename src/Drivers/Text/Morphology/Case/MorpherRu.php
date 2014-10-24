<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Convert', function ($Call)
    {
        $Call['Morpher Lock'] = Root.'/locks/morpher';
        $Result = $Call['Value'];
        $CachedResult = F::Run('IO', 'Read', [
            'Storage' => 'Morpher Ru Cache',
            'Where'   =>
            [
                'ID' => sha1($Call['Value'])
            ]
        ]);
        if ($CachedResult)
            $CachedResult = array_pop($CachedResult);

        if ($CachedResult)
            $WebResult = $CachedResult;
        else
        {
            $Lock = false;

            if (file_exists($Call['Morpher Lock']))
            {
                if (filectime($Call['Morpher Lock'])<(time()-86400))
                {
                    if (unlink($Call['Morpher Lock']))
                        F::Log('Morpher lockfile deleted', LOG_INFO);
                }
                else
                {
                    F::Log('Morpher lockfile found', LOG_INFO);
                    $Lock = true;
                }
            }

            if ($Lock)
                F::Log('Morpher Locked', LOG_INFO);
            else
            {
                $Query = [
                        's' => $Call['Value']
                     ];

                if (isset($Call['MorpherRu']['Auth']))
                    $Query = F::Merge($Query, $Call['MorpherRu']['Auth']);

                $WebResult = F::Run('IO', 'Read', [
                    'Storage' => 'Web',
                    'Where'   => 'http://api.morpher.ru/WebService.asmx/GetXml',
                    'Format'  => 'Formats.XML',
                    'Data'    => $Query
                ]);

                $WebResult = array_pop($WebResult);

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
        }


        if (isset($WebResult['error']) && $WebResult['error']['code'] == 1)
        {
            F::Log('Morpher quota exceeded. Lockfile added.', LOG_INFO);
            touch ($Call['Morpher Lock']);
        }
        else
        {
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
            }
        }



        return $Result;
    });