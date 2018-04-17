<?php

setFn('Decode', function ($Call) {
    $Key = $Call['Key'];
    $Value = $Call['Value'];

    return array_reduce(str_split($Value, 2), function ($Decrypted, $CharCode) use ($Key) {
        $Decrypted .= chr(intval($CharCode, 16) ^ intval($Key));
        return $Decrypted;
    }, '');
});

setFn('Encode', function ($Call) {
    $Key = $Call['Key'];
    $Value = $Call['Value'];

    return array_reduce(str_split($Value), function ($Encrypted, $Letter) use ($Key) {
        $Encrypted .= sprintf("%02x", ord($Letter) ^ intval($Key));
        return $Encrypted;
    }, '');
});