<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Initialize', function ($Call)
    {
        return $Call;
    });
    
    setFn('Encode', function ($Call)
    {
        return serialize($Call['Opentext']);
    });
    
    setFn('Decode', function ($Call)
    {
        return unserialize($Call['Ciphertext']);
    });
    
    setFn('Symmetrical Encode', function ($Call)
    {
        return serialize($Call['Opentext']);
    });
    
    setFn('Symmetrical Decode', function ($Call)
    {
        return unserialize($Call['Ciphertext']);
    });