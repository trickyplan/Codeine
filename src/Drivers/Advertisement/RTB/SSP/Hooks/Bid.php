<?php

    /**
     * Created by PhpStorm.
     * User: Belinskiy Alexey
     * Date: 31.05.17
     * Time: 17:21
     */
    
    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeFillBidRequest', $Call);

        $Call = F::Hook('afterFillBidRequest', $Call);
        
        return $Call;
    });