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
       
        $PublicKey = openssl_get_publickey($Call['Key']);
        
        if (openssl_public_encrypt($Call['Opentext'], $Call['Ciphertext'], $PublicKey, OPENSSL_PKCS1_OAEP_PADDING))
            F::Log('OpenSSL: No errors', LOG_INFO);
        else
            F::Log('OpenSSL: '.openssl_error_string(), LOG_ERR);
        
        $Call = F::Hook('afterEncode', $Call);
        
        return $Call['Ciphertext'];
    });
    
    setFn('Decode', function ($Call)
    {
        $Call = F::Run(null, 'Initialize', $Call);
        
        $Call = F::Hook('beforeDecode', $Call);

            $PrivateKey  = openssl_get_privatekey($Call['Key']);
            
            if (openssl_private_decrypt($Call['Ciphertext'], $Call['Opentext'], $PrivateKey, OPENSSL_PKCS1_OAEP_PADDING))
                F::Log('OpenSSL: No errors', LOG_INFO);
            else
            {
                while ($Message = openssl_error_string())
                    F::Log('OpenSSL: '.$Message, LOG_ERR);
            }
            
        $Call = F::Hook('afterDecode', $Call);
        
        return $Call['Opentext'];
    });
    
    setFn('Symmetrical Encode', function ($Call)
    {
        $Call = F::Hook('beforeSymmetricalEncode', $Call);
        
        if ($Call['Ciphertext'] = openssl_encrypt($Call['Opentext'], 'aes-256-ecb', $Call['Key'])) // FIXME Algo to Options
            F::Log('OpenSSL: No errors', LOG_INFO);
        else
            F::Log('OpenSSL: '.openssl_error_string(), LOG_ERR);
        
        $Call = F::Hook('afterSymmetricalEncode', $Call);
        
        return $Call['Ciphertext'];
    });
    
    setFn('Symmetrical Decode', function ($Call)
    {
        $Call = F::Hook('beforeSymmetricalDecode', $Call);
         
            if ($Call['Opentext'] = openssl_decrypt($Call['Ciphertext'], 'aes-256-ecb', $Call['Key']))
                F::Log('OpenSSL: No errors', LOG_INFO);
            else
            {
                while ($Message = openssl_error_string())
                    F::Log('OpenSSL: '.$Message, LOG_ERR);
            }
            
        $Call = F::Hook('afterSymmetricalDecode', $Call);
        
        return $Call['Opentext'];
    });