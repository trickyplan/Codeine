<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $IX => $Match)
        {
            $IfTag = '<if '.$Call['Parsed'][1][$IX].'></if>';

            $Root = simplexml_load_string($IfTag);

            $Outer = '';

            if ($Root !== false)
                {
                    $Options = iterator_to_array($Root->attributes());

                    $Value = (string) $Options['value'];

                    $Decision = false;

                    if (isset($Options['null']))
                    {
                        if ($Options['null'] == 1)
                            $Decision = (null == $Value);
                        else
                            $Decision = !(null == $Value);
                    }

                    if (isset($Options['eq']))
                        $Decision = ($Value == (string) $Options['eq']);

                    if (isset($Options['neq']))
                        $Decision = ($Value != (string) $Options['neq']);

                    if (isset($Options['lt']))
                        $Decision = ((float) preg_replace('/,/','.',$Value) < (float) $Options['lt']);

                    if (isset($Options['gt']))
                        $Decision = ((float) preg_replace('/,/','.',$Value) > (float) $Options['gt']);

                    if (isset($Options['gt']) && isset($Options['lt']))
                    {
                        $Decision =
                            ((float) preg_replace('/,/','.',$Value) < (float) $Options['lt'])
                            &&
                            ((float) preg_replace('/,/','.',$Value) > (float) $Options['gt']);
                    }

                    if ($Decision)
                        $Outer = $Call['Parsed'][2][$IX];
                    else
                        $Outer = '';
                }
            else
                F::Log($IfTag.' not parsed', LOG_WARNING);

            $Call['Output'] = str_replace ($Call['Parsed'][0][$IX], $Outer, $Call['Output']);
        }

        return $Call;
    });