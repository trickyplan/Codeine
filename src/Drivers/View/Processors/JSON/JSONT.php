<?php

    /* Codeine
     * @author BreathLess
     * @description JSONT Implementation 
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Process', function ($Call)
    {
        $Output = '';

        $Rules = $Call['Rules'];

        F::Map ($Call['Value'], function ($Key, $Value, $Output, $FullKey) use ($Rules)
        {
            $FullKey = substr($FullKey, 1);
            //echo $FullKey."<br/>";
            if (isset($Rules[$FullKey]))
            {
                if (is_callable($Rules[$FullKey]))
                    $Output.= $Rules[$FullKey]($Value);
                else
                {
                    $Output .= preg_replace_callback('/\{(.*)\}/',
                        function ($Matches) use ($Value)
                        {
                            if (empty($Matches[1]))
                                return $Value;
                            else
                                return $Value[$Matches[1]];

                        }, $Rules[$FullKey]);
                }
            }
        }, &$Output);

        return $Output;
    });