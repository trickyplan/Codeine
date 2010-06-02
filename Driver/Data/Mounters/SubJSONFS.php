<?php

function F_SubJSONFS_Mount ($Args)
{
    return $Args['DSN'];
}

function F_SubJSONFS_Unmount ($Args)
{
    return true;
}

function F_SubJSONFS_Create ($Args)
{
    if (!is_dir(Root.$Args['Storage'].'/'.$Args['Dir'].'/'))
        mkdir(Root.$Args['Storage'].'/'.$Args['Dir'].'/', '0777', true);
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.json', json_encode($Args['DDL']['Data']));
}

function F_SubJSONFS_Read ($Args)
{
    $Result = null;
    $FN = $Args['Dir'].'/'.$Args['DDL']['I'].'.json';
    Log::Tap('FS');
    
    if (file_exists(Root.$FN))
        $Result = file_get_contents (Root.$FN);
    elseif (file_exists(Engine.'_Shared/'.$FN))
        $Result = file_get_contents (Engine.'_Shared/'.$FN);
    else
        return Log::Error('SubJSONFS: File '.$FN.' Not Found');
    
    return json_decode($Result);
}

function F_SubJSONFS_Update ($Args)
{
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.json', json_encode($Args['DDL']['Data']));
}

function F_SubJSONFS_Delete ($Args)
{
    Log::Tap('FS');
    return unlink (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.json');
}

function F_SubJSONFS_Exist ($Args)
{
    if (!($Result = file_exists (Root.$Args['Dir'].'/'.$Args['DDL']['I'].'.json')))
        $Result = file_exists (Engine.'_Shared/'.$Args['Dir'].'/'.$Args['DDL']['I'].'.json');
    Log::Tap('FS');
    return $Result;
}