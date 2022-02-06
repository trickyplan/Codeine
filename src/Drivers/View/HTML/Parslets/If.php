<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Layout Parslet
     * @package Codeine
     * @version 8.x
     */

    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Decision = false;

            if (empty($Call['Parsed']['Options'][$IX]))
                ;
            else
            {
                $Value = null;

                $Variable = (string)F::Dot($Call['Parsed'], 'Options.' . $IX . '.variable');

                if (empty($Variable))
                    ;
                else
                    $Value = F::Dot($Call, $Variable);

                if (empty($Value))
                {
                    $Key = (string)F::Dot($Call['Parsed'], 'Options.' . $IX . '.key');
                    if (empty($Key))
                        ;
                    else
                        $Value = F::Dot($Call['Data'], $Key);
                }

                if (empty($Value))
                    $Value = (string)F::Dot($Call['Parsed'], 'Options.' . $IX . '.value');

                if ($Value === 0)
                    $Value = '0';

                if ($Value === false)
                    $Value = 'false';

                if ($Value === true)
                    $Value = 'true';

                if (empty($Value))
                {
                    if ($Null = (string)F::Dot($Call['Parsed'], 'Options.' . $IX . '.null'))
                        $Value = $Null;
                    else
                        $Value = 'null'; // TODO Externalize
                }

                if (isset($Call['Parsed']['Options'][$IX]['null']))
                {
                    if ($Call['Parsed']['Options'][$IX]['null'] == 1)
                        $Decision = (null == $Value);
                    else
                        $Decision = !(null == $Value);
                }


                if (isset($Call['Parsed']['Options'][$IX]['eq']))
                    $Decision = ($Value == (string)$Call['Parsed']['Options'][$IX]['eq']);

                if (isset($Call['Parsed']['Options'][$IX]['neq']))
                    $Decision = ($Value != (string)$Call['Parsed']['Options'][$IX]['neq']);

                if (isset($Call['Parsed']['Options'][$IX]['lt']))
                    $Decision = ((float)preg_replace('/,/', '.', $Value) < (float)$Call['Parsed']['Options'][$IX]['lt']);

                if (isset($Call['Parsed']['Options'][$IX]['gt']))
                    $Decision = ((float)preg_replace('/,/', '.', $Value) > (float)$Call['Parsed']['Options'][$IX]['gt']);

                if (isset($Call['Parsed']['Options'][$IX]['lte']))
                    $Decision = ((float)preg_replace('/,/', '.', $Value) <= (float)$Call['Parsed']['Options'][$IX]['lte']);

                if (isset($Call['Parsed']['Options'][$IX]['gte']))
                    $Decision = ((float)preg_replace('/,/', '.', $Value) >= (float)$Call['Parsed']['Options'][$IX]['gte']);

                if (isset($Call['Parsed']['Options'][$IX]['gt']) && isset($Call['Parsed']['Options'][$IX]['lt']))
                {
                    $Decision =
                        ((float)preg_replace('/,/', '.', $Value) < (float)$Call['Parsed']['Options'][$IX]['lt'])
                        &&
                        ((float)preg_replace('/,/', '.', $Value) > (float)$Call['Parsed']['Options'][$IX]['gt']);
                }

                if (isset($Call['Parsed']['Options'][$IX]['gte']) && isset($Call['Parsed']['Options'][$IX]['lte']))
                {
                    $Decision =
                        ((float)preg_replace('/,/', '.', $Value) <= (float)$Call['Parsed']['Options'][$IX]['lte'])
                        &&
                        ((float)preg_replace('/,/', '.', $Value) >= (float)$Call['Parsed']['Options'][$IX]['gte']);
                }

                if (isset($Call['Parsed']['Options'][$IX]['gt']) && isset($Call['Parsed']['Options'][$IX]['lte']))
                {
                    $Decision =
                        ((float)preg_replace('/,/', '.', $Value) < (float)$Call['Parsed']['Options'][$IX]['lte'])
                        &&
                        ((float)preg_replace('/,/', '.', $Value) >= (float)$Call['Parsed']['Options'][$IX]['gt']);
                }

                if (isset($Call['Parsed']['Options'][$IX]['gte']) && isset($Call['Parsed']['Options'][$IX]['lt']))
                {
                    $Decision =
                        ((float)preg_replace('/,/', '.', $Value) <= (float)$Call['Parsed']['Options'][$IX]['lt'])
                        &&
                        ((float)preg_replace('/,/', '.', $Value) > (float)$Call['Parsed']['Options'][$IX]['gte']);
                }

                if (isset($Call['Parsed']['Options'][$IX]['in']))
                {
                    $Data = F::Dot($Call, $Call['Parsed']['Options'][$IX]['in']);
                    if (is_array($Data))
                    {
                        $Decision = in_array($Value, $Data);
                    }
                }
            }

            if ($Decision)
                $Call['Replace'][$Call['Parsed']['Match'][$IX]] = $Match;
            else
                $Call['Replace'][$Call['Parsed']['Match'][$IX]] = '';
        }

        return $Call;
    });