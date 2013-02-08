<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('Sort', function ($Call)
    {
        if (isset($Call['Request']['sort']))
        {
            $Call['Sort'] = [$Call['Request']['sort'] => 'ASC'];
            $Call['PageURLPostfix'] = '?sort='.$Call['Request']['sort'];
        }
        else
        {
            if( isset($Call['Request']['rsort']))
            {
                $Call['Sort'] = [$Call['Request']['rsort'] => 'DESC'];
                $Call['PageURLPostfix'] = '?rsort='.$Call['Request']['rsort'];
            }
        }

        return $Call;
    });