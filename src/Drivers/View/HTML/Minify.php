<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Minifier 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Process', function ($Call)
    {
        $Call['Output'] = preg_replace ('/~<pre>\\s{2,}|\\s{2,}$/m', '', $Call['Output']);
        return $Call;
    });