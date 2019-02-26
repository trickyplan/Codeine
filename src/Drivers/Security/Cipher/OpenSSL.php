<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('List.Cipher.Methods', function ($Call)
    {
        return openssl_get_cipher_methods();
    });
    
    setFn('Test.Cipher.Methods', function ($Call)
    {
        $Algorithms = openssl_get_cipher_methods();
        $Results = [];
        foreach ($Algorithms as $CipherAlgorithm)
        {
            F::Start($CipherAlgorithm);
            {
                $IVLength = openssl_cipher_iv_length($CipherAlgorithm);
                $IV = openssl_random_pseudo_bytes($IVLength);
                $Key = openssl_random_pseudo_bytes($IVLength*2);
                for ($IX = 0; $IX < 1000; $IX++)
                    $Ciphered = openssl_encrypt(sha1(rand(0,PHP_INT_MAX)), $CipherAlgorithm, $Key, 0, $IV);
            }
                
            F::Stop($CipherAlgorithm);
            $Results[$CipherAlgorithm] = F::Time($CipherAlgorithm);
        }
        asort($Results);
        
        return $Results;
    });
    
    setFn('Initialize', function ($Call)
    {
        if (isset($Call['Key']))
            ;
        else
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
       
            if (isset($Call['Cipher']['IV']))
                F::Log('IV is set', LOG_INFO, 'Security');
            else
            {
                F::Log('IV is generated', LOG_INFO, 'Security');
                $IVLength = openssl_cipher_iv_length($Call['Cipher']['Algorithm']);
                $Call['Cipher']['IV'] = openssl_random_pseudo_bytes($IVLength);
            }
            
            if ($Call['Ciphertext'] = openssl_encrypt($Call['Opentext'], $Call['Cipher']['Algorithm'], $Call['Key'], OPENSSL_RAW_DATA, $Call['Cipher']['IV']))
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
         
            if ($Call['Opentext'] = openssl_decrypt($Call['Ciphertext'], $Call['Cipher']['Algorithm'], $Call['Key'], OPENSSL_RAW_DATA, $Call['Cipher']['IV']))
                F::Log('OpenSSL: No errors', LOG_INFO);
            else
            {
                while ($Message = openssl_error_string())
                    F::Log('OpenSSL: '.$Message, LOG_ERR);
            }
            
        $Call = F::Hook('afterDecode', $Call);
        
        return $Call['Opentext'];
    });