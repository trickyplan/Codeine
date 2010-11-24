<?php

function F_SubXMLFS_Mount ($Args)
{
    return $Args['DSN'];
}

function F_SubXMLFS_Unmount ($Args)
{
    return true;
}

function F_SubXMLFS_Create ($Args)
{
    if (!is_dir(Root.$Args['Storage'].'/'.$Args['Dir'].'/'))
        mkdir(Root.$Args['Storage'].'/'.$Args['Dir'].'/', '0777', true);
    // TODO XML Convert
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.xml', $Args['DDL']['Data']);
}

function F_SubXMLFS_Read ($Args)
{
    $Result = null;
    
    $FN = $Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.xml';
    
    if (file_exists(Root.$FN))
        $Result = file_get_contents (Root.$FN);
    elseif (file_exists(Engine.'_Shared/'.$FN))
        $Result = file_get_contents (Engine.'_Shared/'.$FN);
    Log::Tap('FS');
    return simplexml_load_string($Result);
}

function F_SubXMLFS_Update ($Args)
{
    // TODO XML Convert
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.xml', $Args['DDL']['Data']);
}

function F_SubXMLFS_Delete ($Args)
{
    Log::Tap('FS');
    return unlink (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.xml');
}

function F_SubXMLFS_Exist ($Args)
{
    if (!($Result = file_exists (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.xml')))
        $Result = file_exists (Engine.'_Shared/'.$Args['Storage'].$Args['Dir'].$Args['DDL']['I'].'.xml');
    Log::Tap('FS');
    return $Result;
}