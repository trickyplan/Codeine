<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Process Page', function ($Call)
    {
        $Parsed = F::Run('Text.Regex', 'All',
            [
                'Pattern' => $Call['Image']['Pattern'],
                'Value' => $Call['Output']
            ]);

        if ($Parsed)
        {
            if (isset($Call['Image']['Host']) && !empty($Call['Image']['Host']))
                ;
            else
                $Call['Image']['Host'] = $Call['HTTP']['Host'];

            $Call['Images'] = $Parsed[2];

            // Перед вводом картинок
            $Call = F::Hook('beforeImageInput', $Call);

                // Чтение тегов
                foreach ($Call['Images'] as $IX => &$Image)
                {
                    $Format = $Call['Image']['Tag Format'];

                    $Root = simplexml_load_string('<image'.$Parsed[1][$IX].'></image>');
                    if (isset($Root->attributes()->type))
                        $Format = (string) $Root->attributes()->type;

                    $Image = F::Merge($Call['Image'],
                        F::Run('Formats.'.$Format, 'Read', ['Value' => $Image])
                    );

                    $Image['Source']['Scope'] = strtr($Image['Source']['Scope'], '.', DS);
                }
            // После ввода картинок
            $Call = F::Hook('afterImageInput', $Call);

            // Перед выводом картинок
            $Call = F::Hook('beforeImageOutput', $Call);

                foreach ($Call['Images'] as $Call['Current Image'])
                    $Call['Image']['Tags'][] = F::Run(null, 'Process Image', $Call);

            $Call = F::Hook('afterImageOutput', $Call);

            if (empty($Call['Image']['Tags']))
                $Call['Image']['Tags'] = '';

            $Call['Output'] = str_replace($Parsed[0], $Call['Image']['Tags'] , $Call['Output']);

            unset($Call['Current Image'], $Call['Image']);
        }

        return $Call;
    });

    setFn('Output Name', function ($Call)
    {
        $Call['Image']['Fullpath'] = $Call['Image']['Path Separator'].implode($Call['Image']['Path Separator'],
            [
                F::Run('IO', 'Execute', $Call['Current Image']['Source'],['Execute' => 'Version']),
                            (isset($Call['Current Image']['Width'])? $Call['Current Image']['Width']: 0),
                            (isset($Call['Current Image']['Height'])? $Call['Current Image']['Height']: 0),
                            isset($Call['Current Image']['Source']['Scope'])? $Call['Current Image']['Source']['Scope']: '',
                            sha1($Call['Current Image']['Source']['Where']['ID'])
                            .'.'.pathinfo(parse_url($Call['Current Image']['Source']['Where']['ID'], PHP_URL_PATH), PATHINFO_EXTENSION)
            ]);

        return $Call;
    });

    setFn('Process Image', function ($Call)
    {
        if (isset($Call['Current Image']['Source']['Where'])
            && !empty($Call['Current Image']['Source']['Where']))
                $Call['Current Image']['Source']['Where'] =
                        ['ID' => $Call['Current Image']['Source']['Where']];
                else
                    $Call['Current Image']['Source']['Where'] = null;

        // Generate Output Name
        $Call = F::Apply(null, 'Output Name', $Call);

        $Call['Image']['Scope'] = '';

        $FullPath = sha1($Call['Image']['Fullpath']);
        for ($IX = 0; $IX < $Call['Image']['Hash Levels']; $IX++)
            $Call['Image']['Scope'].= substr($FullPath, $IX, 1).'/';

        if (F::Run('IO', 'Execute',
                        [
                            'Storage' => 'Image Cache',
                            'Scope'   => [
                                $Call['Image']['Host'],
                                $Call['Image']['Directory'],
                                $Call['Image']['Scope']],
                            'Execute' => 'Exist',
                            'Where'   =>
                            [
                                'ID' => $Call['Image']['Fullpath']
                            ]
                        ]))
            F::Log('Image Cache *hit* '.$Call['Image']['Fullpath'], LOG_DEBUG);
        else
        {
            F::Log('Image Cache *miss* *'.$Call['Image']['Fullpath'].'*', LOG_NOTICE);

            if (F::Run(null, 'Write', $Call))
            {

            }
            else
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
                    $Call['Current Image']['Source']['Scope'] = 'Default/'.$Call['Image']['Directory'];
                    $Call['Current Image']['Source']['Where'] = ['ID' => 'Default.png'];
                }

                $Call = F::Apply(null, 'Output Name', $Call);
                if (F::Run(null, 'Write', $Call))
                    ;
                else
                {
                    $Call['Current Image']['Source']['Scope'] = 'Default/'.$Call['Image']['Directory'];
                    $Call['Current Image']['Source']['Where'] = ['ID' => 'Default.png'];

                    $Call = F::Apply(null, 'Output Name', $Call);
                    F::Run(null, 'Write', $Call);
                }
            }
        }

        $Call['Current Image']['Widget'] = [];
        $SRC = $Call['Image']['Pathname'].$Call['Image']['Scope'].$Call['Image']['Fullpath'];

        if (isset($Call['Image']['Host']) && !empty($Call['Image']['Host']))
            $SRC = $Call['HTTP']['Proto']
                .$Call['Image']['Host']
                .$SRC;

        if (empty($Call['Current Image']['Alt']))
            F::Log('Image: Alt is empty for '.$Call['Image']['Fullpath'], LOG_INFO);
        else
            if (is_string($Call['Current Image']['Alt']))
                ;
            else
            {
                F::Log('Incorrect image alt at '.j($Call['Current Image']).', erased', LOG_WARNING);
                $Call['Current Image']['Alt'] = '';
            }

        if (isset($Call['Current Image']['Return Image Path']) && $Call['Current Image']['Return Image Path'])
            return $SRC;
        else
        {
            $Call['Current Image']['Widget']['src'] = $SRC;
            $Call['Current Image']['Widget']['alt'] = $Call['Current Image']['Alt'];
            $Call['Current Image']['Widget']['class'] = $Call['Current Image']['Class'];

            if (isset($Call['Current Image']['Height']))
                $Call['Current Image']['Widget']['height'] = $Call['Current Image']['Height'];

            if (isset($Call['Current Image']['Width']))
                $Call['Current Image']['Widget']['width'] = $Call['Current Image']['Width'];

            $Call = F::Hook('beforeWidgetMake', $Call);
            return F::Run('View.HTML.Widget.Image', 'Make', $Call['Current Image']['Widget']);
        }
    });

    setFn('Write', function ($Call)
    {
        if (null === $Call['Current Image']['Source']['Where'] ||
            !F::Run('IO', 'Execute', ['Execute' => 'Exist'], $Call['Current Image']['Source']))
        {
            F::Log('Image not found:'.$Call['Current Image']['Source']['Where']['ID'], LOG_INFO);
            return null;
        }

        $Call['Current Image']['Data'] =
            F::Run('IO', 'Read', $Call['Current Image']['Source'])[0];

        $Call = F::Hook('beforeImageWrite', $Call);

            F::Run ('IO', 'Write',
            [
                 'Storage' => 'Image Cache',
                 'Scope'   => [$Call['Image']['Host'], $Call['Image']['Directory'], $Call['Image']['Scope']],
                 'Where'   => $Call['Image']['Fullpath'],
                 'Data'    => $Call['Current Image']['Data']
            ]);

        $Call = F::Hook('afterImageWrite', $Call);

        return $Call;
    });