<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Encode', function ($Call)
    {
        return strtr(base64_encode($Call['Value'].$Call['BASE64URLSafe']['Salt']), '+/=', '-_~');
    });
    
    setFn('Decode', function ($Call)
    {
        $Call['Value'] = base64_decode(strtr($Call['Value'], '-_~', '+/='));
        
        if ($Call['Value'] === false)
            ;
        else
        {
            $SaltLength = mb_strlen($Call['BASE64URLSafe']['Salt']);
            $TextLength = mb_strlen($Call['Value']);
            $TextLength -= $SaltLength;
            
            if (mb_strpos($Call['Value'], $Call['BASE64URLSafe']['Salt']) == ($TextLength))
                $Call['Value'] = mb_substr($Call['Value'], 0, $TextLength);
            else
                $Call['Value'] = 'Salt isn\'t detected';
        }
        
        return $Call['Value'];
    });