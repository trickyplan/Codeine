<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        $Result = '';
        $Size = mt_rand($Call['Size']['Min'], $Call['Size']['Max']);

        for ($IX = 0; $IX < $Size; $IX++)
            $Result.= $Call['Entropy']['Letters']['Chars']['N'][array_rand($Call['Entropy']['Letters']['Chars']['N'])];

        return $Result;
    });