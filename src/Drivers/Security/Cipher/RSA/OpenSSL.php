<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Initialize', function ($Call)
    {
        $Call['Key'] = F::Run('IO', 'Read',
            [
                'Storage'   => 'Keys',
                'Where'     => $Call['KeyID'],
                'IO One'    => true
            ]);
        
        return $Call;
    });
    
    setFn('Encode', function ($Call)
    {
        $Call = F::Run(null, 'Initialize', $Call);
        
        $Call = F::Hook('beforeEncode', $Call);
        
        $PublicKey  = openssl_get_publickey($Call['Key']);
        
        if (openssl_public_encrypt($Call['Opentext'], $Call['Ciphertext'], $PublicKey))
            F::Log('OpenSSL: No errors', LOG_INFO);
        else
            F::Log('OpenSSL: '.openssl_error_string(), LOG_ERR);
        
        $Call = F::Hook('afterEncode', $Call);
        
        return chunk_split($Call['Ciphertext']);
    });
    
    setFn('Decode', function ($Call)
    {
        $Call = F::Run(null, 'Initialize', $Call);
        
        $Call = F::Hook('beforeDecode', $Call);

            $PrivateKey  = openssl_get_privatekey($Call['Key']);
            
            if (openssl_private_decrypt($Call['Ciphertext'], $Call['Opentext'], $PrivateKey))
                F::Log('OpenSSL: No errors', LOG_INFO);
            else
            {
                while ($Message = openssl_error_string())
                    F::Log('OpenSSL: '.$Message, LOG_ERR);
            }
            
        $Call = F::Hook('afterDecode', $Call);
        
        return $Call['Opentext'];
    });