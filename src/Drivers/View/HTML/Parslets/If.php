<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Parse', function ($Call)
    {
        $Replaces = [];

        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            if (empty($Call['Parsed']['Options'][$IX]))
                ;
            else
            {
                $Value = (string) $Call['Parsed']['Options'][$IX]['value'];

                $Decision = false;

                if (isset($Call['Parsed']['Options'][$IX]['null']))
                {
                    if ($Call['Parsed']['Options'][$IX]['null'] == 1)
                        $Decision = (null == $Value);
                    else
                        $Decision = !(null == $Value);
                }

                if (isset($Call['Parsed']['Options'][$IX]['eq']))
                    $Decision = ($Value == (string) $Call['Parsed']['Options'][$IX]['eq']);

                if (isset($Call['Parsed']['Options'][$IX]['neq']))
                    $Decision = ($Value != (string) $Call['Parsed']['Options'][$IX]['neq']);

                if (isset($Call['Parsed']['Options'][$IX]['lt']))
                    $Decision = ((float) preg_replace('/,/','.',$Value) < (float) $Call['Parsed']['Options'][$IX]['lt']);

                if (isset($Call['Parsed']['Options'][$IX]['gt']))
                    $Decision = ((float) preg_replace('/,/','.',$Value) > (float) $Call['Parsed']['Options'][$IX]['gt']);

                if (isset($Call['Parsed']['Options'][$IX]['lte']))
                    $Decision = ((float) preg_replace('/,/','.',$Value) <= (float) $Call['Parsed']['Options'][$IX]['lte']);

                if (isset($Call['Parsed']['Options'][$IX]['gte']))
                    $Decision = ((float) preg_replace('/,/','.',$Value) >= (float) $Call['Parsed']['Options'][$IX]['gte']);
                
                if (isset($Call['Parsed']['Options'][$IX]['gt']) && isset($Call['Parsed']['Options'][$IX]['lt']))
                {
                    $Decision =
                        ((float) preg_replace('/,/','.',$Value) < (float) $Call['Parsed']['Options'][$IX]['lt'])
                        &&
                        ((float) preg_replace('/,/','.',$Value) > (float) $Call['Parsed']['Options'][$IX]['gt']);
                }
                
                if (isset($Call['Parsed']['Options'][$IX]['gte']) && isset($Call['Parsed']['Options'][$IX]['lte']))
                {
                    $Decision =
                        ((float) preg_replace('/,/','.',$Value) <= (float) $Call['Parsed']['Options'][$IX]['lte'])
                        &&
                        ((float) preg_replace('/,/','.',$Value) >= (float) $Call['Parsed']['Options'][$IX]['gte']);
                }

                if ($Decision)
                    $Replaces[$IX] = $Match;
                else
                    $Replaces[$IX] = '';
            }
        }

        return $Replaces;
    });