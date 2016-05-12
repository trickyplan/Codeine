<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        $Result = $Call['Entropy']['Variable']['Chars']['1'][array_rand($Call['Entropy']['Variable']['Chars']['1'])];
        $Size = mt_rand($Call['Size']['Min'], $Call['Size']['Max']);

        for ($IX = 0; $IX < $Size; $IX++)
            $Result.= $Call['Entropy']['Variable']['Chars']['N'][array_rand($Call['Entropy']['Variable']['Chars']['N'])];

        return $Result;
    });