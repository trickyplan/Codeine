<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Hash', function ($Call)
    {
        $Hash = array ();

        foreach ($Call['IDs'] as $CSSFile)
        {
            list($Asset, $ID) = F::Run('View', 'Asset.Route', array ('Value' => $CSSFile));

            $Hash[] = $CSSFile .F::Run('IO', 'Execute', array (
                                                               'Storage' => 'CSS',
                                                               'Scope'   => $Asset.'/css',
                                                               'Execute' => 'Version',
                                                               'Where'   =>
                                                                   array (
                                                                       'ID' => $ID
                                                                   )
                                                         ));
        }

        return F::Run('Security.Hash', 'Get', array('Value' => implode('', $Hash)));
    });

    self::setFn ('Process', function ($Call)
    {
        $CSS = array();

        $ImageGenerateCSS = '';

        if (preg_match_all('@<style(.*)>(.*)<\/style>@SsUu', $Call['Output'], $StyleTags))
        {
            foreach($StyleTags[2] as $Key=>$CSSStyle){

                if(trim($StyleTags[1][$Key])=='type="text/css-generate"')
                        $ImageGenerateCSS = $CSSStyle;
                    else
                        $CSS[] = $CSSStyle;
            }

            $Call['Output'] = str_replace($StyleTags[0], '', $Call['Output']);

        }

        if (preg_match_all ('@<css>(.*)<\/css>@SsUu', $Call['Output'], $Parsed))
        {
            $Parsed[1] = array_unique($Parsed[1]);

            $CSSHash = F::Run(null, 'Hash', array('IDs' => $Parsed[1])).sha1(implode('',$CSS));

            $CSS[] = $ImageGenerateCSS;

                if ((isset($Call['Caching']['Enabled'])
                    && $Call['Caching']['Enabled'])
                    && F::Run('IO', 'Execute', array ('Storage' => 'CSS Cache',
                                                     'Execute'  => 'Exist',
                                                     'Where'    => array ('ID' => $CSSHash)))
                )
                {

                }
                else
                {

                    foreach ($Parsed[1] as $CSSFile)
                    {
                        list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $CSSFile));

                        if ($CSSSource = F::Run('IO', 'Read', array (
                                                                    'Storage' => 'CSS',
                                                                    'Scope'   => $Asset . '/css',
                                                                    'Where'   => $ID
                                                              )))
                        {
                            F::Log('CSS loaded: '.$CSSFile);
                            $CSS[] = $CSSSource[0];
                        }
                        else
                            trigger_error('No CSS: '.$CSSFile); // FIXNE
                    }

                    $CSS = implode ('', $CSS);

                    if (isset($Call['Postprocessors']) && $Call['Postprocessors'])
                        foreach($Call['Postprocessors'] as $Processor)
                            $CSS = F::Run($Processor['Service'], $Processor['Method'], array('Value' => $CSS));

                    F::Run ('IO', 'Write',
                            array(
                                 'Storage' => 'CSS Cache',
                                 'Where'   => $CSSHash,
                                 'Data' => $CSS
                            ));

                }

                $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);

            if (strpos($Call['Output'], '<place>CSS</place>') === false)
                trigger_error('Place for CSS missed');

            $Call['Output'] = str_replace('<place>CSS</place>', '<link href="'.$Call['CSS Host'].'/cache/css/'.$CSSHash.'.css" rel="stylesheet" />',
                $Call['Output']);
        }
        else
            $Call['Output'] = str_replace('<place>CSS</place>', '', $Call['Output']);

        return $Call;
    });