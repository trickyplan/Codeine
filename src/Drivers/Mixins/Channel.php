<?php
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        if (isset($Call['Session']['User']['Channel']))
            $Channel = $Call['Session']['User']['Channel'];
        elseif (isset($Call['Session']['Channel']))
            $Channel = $Call['Session']['Channel'];
        else
            $Channel = $Call['Default Channel'];

        return $Channel;
    });