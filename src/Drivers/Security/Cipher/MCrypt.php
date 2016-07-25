<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Initialize', function ($Call)
    {
        $Call['IV Size'] = mcrypt_get_iv_size($Call['Security']['Cipher']['MCrypt']['Algorithm'], $Call['Security']['Cipher']['MCrypt']['Mode']);
        
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
        $Call = F::Apply(null, 'Initialize', $Call);
        
        $Call = F::Hook('beforeEncode', $Call);

            if (isset($Call['IV']))
                ;
            else
                $Call['IV']  = mcrypt_create_iv($Call['IV Size'], MCRYPT_DEV_URANDOM);
                
            $Call['Ciphertext'] = mcrypt_encrypt($Call['Security']['Cipher']['MCrypt']['Algorithm'], $Call['Key'], $Call['Opentext'], $Call['Security']['Cipher']['MCrypt']['Mode'], $Call['IV']);
            $Call['Ciphertext'] = $Call['IV'].$Call['Ciphertext'];
        
        $Call = F::Hook('afterEncode', $Call);
        
        return $Call['Ciphertext'];
    });
    
    setFn('Decode', function ($Call)
    {
        $Call = F::Apply(null, 'Initialize', $Call);
        
        $Call = F::Hook('beforeDecode', $Call);

            if (isset($Call['IV']))
                ;
            else
                $Call['IV'] = substr($Call['Ciphertext'], 0, $Call['IV Size']);

            $Call['Ciphertext'] = substr($Call['Ciphertext'], $Call['IV Size']);
            
            $Call['Opentext'] = mcrypt_decrypt($Call['Security']['Cipher']['MCrypt']['Algorithm']
                , $Call['Key']
                , $Call['Ciphertext']
                , $Call['Security']['Cipher']['MCrypt']['Mode']
                , $Call['IV']);

        $Call['Opentext'] = rtrim($Call['Opentext'], "\0");
       
        $Call = F::Hook('afterDecode', $Call);
        
        return $Call['Opentext'];
    });
    
/*
public static function mydecrypt($crypted, $key) {
  if (empty($crypted)) {
    trigger_error('Decrypt empty data');
    return '';
  }
  
  $ciphertext_dec = base64_decode($crypted);
  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
  $iv_dec = substr($ciphertext_dec, 0, $iv_size);

  $ciphertext_dec = substr($ciphertext_dec, $iv_size);
  $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

  return rtrim($plaintext_dec, "\0");
}
     */