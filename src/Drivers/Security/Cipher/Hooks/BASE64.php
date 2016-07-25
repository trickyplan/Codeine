<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('afterEncode', function ($Call)
    {
        $Call['Ciphertext'] = base64_encode($Call['Ciphertext']);
        return $Call;
    });
    
    setFn('beforeDecode', function ($Call)
    {
        $Call['Ciphertext'] = base64_decode($Call['Ciphertext']);
        return $Call;
    });