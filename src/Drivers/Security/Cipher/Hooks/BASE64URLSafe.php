<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('afterEncode', function ($Call)
    {
        $Call['Ciphertext'] = F::Run('Formats.BASE64URLSafe', 'Encode', $Call, ['Value' => $Call['Ciphertext']]);
        return $Call;
    });
    
    setFn('beforeDecode', function ($Call)
    {
        $Call['Ciphertext'] = F::Run('Formats.BASE64URLSafe', 'Decode', $Call, ['Value' => $Call['Ciphertext']]);
        return $Call;
    });