<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Pagination hooks 
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeOperation', function ($Call)
    {
        if (isset($Call['Since']))
        {
            $Since = strptime($Call['Since'],'%x');
            $Call['Where']['Created'] = ['$gt' => mktime(0, 0, 0, $Since['tm_mon'], $Since['tm_,day'], $Since['tm_year'])];
        }

        if (isset($Call['Until']))
        {
            $Since = strptime($Call['Until'],'%x');
            $Call['Where']['Created'] = ['$lt' => mktime(0, 0, 0, $Since['tm_mon'], $Since['tm_,day'], $Since['tm_year'])];
        }

        return $Call;
    });