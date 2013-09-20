<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (F::file_exists(Root.'/Options/Version.json'))
            $Call['Project']['MTime'] = filemtime(Root.'/Options/Version.json');
        return $Call;
    });

    setFn('Down', function ($Call)
    {
        setcookie('Magic', true);
        $DownFile = Root.'/locks/down';

        if (file_exists($DownFile))
        {
            if (unlink($DownFile))
                $Call = F::Hook('Enable.Success', $Call);
            else
                $Call = F::Hook('Enable.Fail', $Call);
        }
        else
        {
            if (file_put_contents($DownFile, time().' '.print_r($Call['Session'], true)))
                $Call = F::Hook('Disable.Success', $Call);
            else
                $Call = F::Hook('Disable.Fail', $Call);
        }

        return $Call;
    });

    setFn('Menu', function ($Call)
    {
        if (isset($Call['Version']))
            return ['Count' => $Call['Version']['Project']['Major'].'.'.$Call['Version']['Project']['Minor']];
    });