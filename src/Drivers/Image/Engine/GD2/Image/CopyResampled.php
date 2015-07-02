<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        imagecopyresampled(
                $Call['Image']['Object'],
                $Call['Second Image']['Object'],
                $Call['X'],
                $Call['Y'],
                $Call['X2'],
                $Call['Y2'],
                $Call['Width'],
                $Call['Height'],
                $Call['Second Image']['Width'],
                $Call['Height'] * ($Call['Second Image']['Width'] / $Call['Width']));

        return $Call['Image'];
    });