<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     * @date 13.08.11
     * @time 22:37
     */

    setFn ('Open', function ($Call)
    {
        return file(F::findFile($Call['Filename']));
    });

    setFn ('Read', function ($Call)
    {
        if (isset($Call['Where']['ID']))
            return $Call['Link'][$Call['Where']['ID']];
        else
            return $Call['Link'];
    });

    setFn ('Write', function ($Call)
    {
        if (isset($Call['Where']['ID']))
            $Call['Link'][$Call['Where']['ID']] = implode(PHP_EOL, $Call['Data']);
        else
            $Call['Link'] = implode(PHP_EOL, $Call['Data']);

        if ($Call['Link'] === null)
            return unlink($Call['Filename']);
        else
            return file_put_contents(Root.$Call['Filename'], $Call['Link']);
    });

    setFn ('Close', function ($Call)
    {
        return true;
    });