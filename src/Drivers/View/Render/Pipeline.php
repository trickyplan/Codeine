<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn('Process', function ($Call)
    {
        if (preg_match_all('@<place>(.*)<\/place>@SsUu', $Call['Layout'], $Places))
        {
            if (isset($Call['Output']))
            {
                if (is_array($Call['Output']))
                    foreach ($Call['Output'] as $Place => $Widgets)
                        foreach ($Widgets as $Key => $Widget)
                            $Call['Output'][$Place][$Key] = F::Run($Call['Renderer'] . '.' . $Widget['Type'], 'Make', $Widget);
            }
            else
                $Call['Output']['Content'] = array ('No output'); // FIXME Add Hook

            foreach ($Call['Output'] as $Place => $Widgets)
                $Call['Layout'] = str_replace('<place>' . $Place . '</place>', implode('', $Widgets), $Call['Layout']);
        }

        $Call['Layout'] = preg_replace('@<place>(.*)<\/place>@SsUu', '', $Call['Layout']);
        $Call['Output'] = $Call['Layout'];

        return $Call;
    });