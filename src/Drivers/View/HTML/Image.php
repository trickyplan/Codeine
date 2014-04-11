<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        $Parsed = F::Run('Text.Regex', 'All',
            [
                'Pattern' => $Call['Image']['Pattern'],
                'Value' => $Call['Output']
            ]);

        if ($Parsed)
        {
            // Image Output
            // Если изображения на отдельном сервере

            if (isset($Call['Image']['Host']) && !empty($Call['Image']['Host']))
                $Host = $Call['Image']['Host'];
            else
                $Host = $Call['HTTP']['Host'];

            $Call['Images'] = $Parsed[1];

            // Перед вводом картинок
            $Call = F::Hook('beforeImageInput', $Call);

            // Чтение тегов
            foreach ($Call['Images'] as &$Image)
                $Image = F::Merge($Call['Image'],
                    json_decode(
                        json_encode(
                            simplexml_load_string('<image>'.$Image.'</image>'),
                            JSON_NUMERIC_CHECK
                        ), true)
                );

            // После ввода картинок
            $Call = F::Hook('afterImageInput', $Call);

            // Перед выводом картинок
            $Call = F::Hook('beforeImageOutput', $Call);

                foreach ($Call['Images'] as $Call['Current Image'])
                {
                    if (isset($Call['Current Image']['Source']['Where']))
                        $Call['Current Image']['Source']['Where'] =
                            ['ID' => $Call['Current Image']['Source']['Where']];
                    else
                        $Call['Current Image']['Source']['Where'] = null;

                    if (!F::Run('IO', 'Execute', $Call['Current Image']['Source'],
                    [
                        'Execute' => 'Exist'
                    ]))
                    {
                        $Call['Current Image']['Storage'] = 'Image';
                        $Call['Current Image']['Scope'] = 'Default';

                        $Call['Current Image']['Source']['Storage'] = 'Image';

                        if (isset($Call['Current Image']['Default']))
                        {
                            list($Asset, $ID) =
                                F::Run('View', 'Asset.Route',
                                [
                                    'Value' => $Call['Current Image']['Default']
                                ]);

                            $Call['Current Image']['Source']['Scope'] = $Asset;
                            $Call['Current Image']['Source']['Where'] = ['ID' => $ID];
                        }
                        else
                        {
                            $Call['Current Image']['Source']['Scope'] = 'Default/img';
                            $Call['Current Image']['Source']['Where'] = ['ID' => 'Default.png'];
                        }
                    }

                    $Version = F::Run('IO', 'Execute', $Call['Current Image']['Source'],
                        [
                            'Execute' => 'Version'
                        ]);

                    $Call['Image']['Cached'] = $Version.'_'.
                        (isset($Call['Current Image']['Width'])? $Call['Current Image']['Width']: 0).
                        'x'.
                        (isset($Call['Current Image']['Height'])? $Call['Current Image']['Height']: 0).
                        strtr($Call['Current Image']['Source']['Scope'].'.'.$Call['Current Image']['Source']['Where']['ID'], '/', '.');

                    $Write = true;

                    if ($Call['Image']['Caching'])
                    {
                        if (F::Run('IO', 'Execute',
                        [
                            'Storage' => 'Image Cache',
                            'Scope'   => [$Host, 'img'],
                            'Execute' => 'Exist',
                            'Where'   =>
                            [
                                'ID' => $Call['Image']['Cached']
                            ]
                        ]))
                        {
                            F::Log('Cache *hit* '.$Call['Image']['Cached'], LOG_GOOD);
                            $Write = false;
                        }
                        else
                        {
                            F::Log('Cache *miss* *'.$Call['Image']['Cached'].'*', LOG_BAD);
                        }
                    }

                    if ($Write)
                    {
                        $Call['Current Image']['Data'] =
                            F::Run('IO', 'Read',
                                $Call['Current Image']['Source']
                            )[0];

                        $Call = F::Hook('beforeImageWrite', $Call);

                            F::Run ('IO', 'Write',
                            [
                                 'Storage' => 'Image Cache',
                                 'Scope'   => [$Host, 'img'],
                                 'Where'   => $Call['Image']['Cached'],
                                 'Data' => $Call['Current Image']['Data']
                            ]);

                        $Call = F::Hook('afterImageWrite', $Call);
                    }

                    if (empty($Call['Current Image']['Alt']))
                        F::Log('Image: Alt is empty for '.$Call['Image']['Cached'], LOG_INFO);

                    $SRC = $Call['Image']['Pathname'].$Call['Image']['Cached'];

                    if (isset($Call['Image']['Host']) && !empty($Call['Image']['Host']))
                        $SRC = $Call['HTTP']['Proto']
                            .$Call['Image']['Host']
                            .$SRC;

                    $Call['Image']['Tags'][] = '<img src="'
                        .$SRC.'"
                        alt="'.$Call['Image']['Alt'].'"
                        class="'.$Call['Current Image']['Class'].'" '
                        .(isset($Call['Current Image']['Height'])? ' height="'.$Call['Current Image']['Height'].'"': ' ')
                        .(isset($Call['Current Image']['Width'])? ' width="'.$Call['Current Image']['Width'].'"': ' ').'/>';

                }

            $Call = F::Hook('afterImageOutput', $Call);

            // После вывода картинок
        }

        if (empty($Call['Image']['Tags']))
            $Call['Image']['Tags'] = '';

        $Call['Output'] = str_replace($Parsed[0], $Call['Image']['Tags'] , $Call['Output']);

        unset($Call['Current Image'], $Call['Image']);

        return $Call;
    });