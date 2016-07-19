<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Encode', function ($Call)
    {
        $Call['Key'] = F::Run('IO', 'Read',
            [
                'Storage'   => 'Keys',
                'Where'     => $Call['KeyID'],
                'IO One'    => true
            ]);
       
        $PublicKey  = openssl_get_publickey($Call['Key']);
        
        if (openssl_public_encrypt($Call['Opentext'], $Call['Ciphertext'], $PublicKey))
            F::Log('OpenSSL: No errors', LOG_INFO);
        else
            F::Log('OpenSSL: '.openssl_error_string(), LOG_ERR);
        
        return chunk_split(base64_encode($Call['Ciphertext']));
    });
    
    setFn('Decode', function ($Call)
    {
        $Call['Key'] = F::Run('IO', 'Read',
            [
                'Storage'   => 'Keys',
                'Where'     => $Call['KeyID'],
                'IO One'    => true
            ]);
       
        $PrivateKey  = openssl_get_privatekey($Call['Key']);
        
        if (openssl_private_decrypt(base64_decode($Call['Ciphertext']), $Call['Opentext'], $PrivateKey))
            F::Log('OpenSSL: No errors', LOG_INFO);
        else
        {
            while ($Message = openssl_error_string())
                F::Log('OpenSSL: '.$Message, LOG_ERR);
        }
        
        return $Call['Opentext'];
    });