<?php

function F_HTTP_Mount ($Args)
{
    return curl_init();
}

function F_HTTP_Unmount ($Args)
{
    return curl_close($Args['Storage']);
}

function F_HTTP_Read ($Args)
{
    curl_setopt($Args['Storage'], CURLOPT_HEADER, false);
    curl_setopt($Args['Storage'], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($Args['Storage'], CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($Args['Storage'], CURLOPT_CONNECTTIMEOUT, 15 );
    curl_setopt($Args['Storage'], CURLOPT_URL, $Args['DDL']['I']);
    Log::Tap('HTTP');
    return curl_exec($Args['Storage']);

}

function F_HTTP_Create ($Args)
{
    $Params = array();
    foreach($Args['DDL'] as $Key => $Value)
        $Params[] = urlencode($Key).'='.urlencode($Value);

    curl_setopt($Args['Storage'], CURLOPT_URL, $Args['DDL']['I']);
    curl_setopt($Args['Storage'], CURLOPT_POST, true);
    curl_setopt($Args['Storage'], CURLOPT_HEADER, false);
    curl_setopt($Args['Storage'], CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($Args['Storage'], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($Args['Storage'], CURLOPT_POSTFIELDS, implode('&',$Params));
    Log::Tap('HTTP');
    return curl_exec($Args['Storage']);
}

function F_HTTP_Update ($Args)
{
    // TODO: HTTP UPDATE
}

function F_HTTP_Delete ($Args)
{
    // TODO: HTTP DELETE
}
