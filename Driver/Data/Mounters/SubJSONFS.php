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
    
    $Result = file_get_contents(Server::Locate('Data', $FN));
    
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