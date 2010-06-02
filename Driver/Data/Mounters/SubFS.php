<?php

function F_SubFS_Mount ($Args)
{
    return $Args['DSN'];
}

function F_SubFS_Unmount ($Args)
{
    return true;
}

function F_SubFS_Create ($Args)
{
    if (!is_dir(Root.$Args['Storage'].'/'.$Args['Dir'].'/'))
        mkdir(Root.$Args['Storage'].'/'.$Args['Dir'].'/', '0777', true);
    Log::Tap('FS');
    return file_put_contents (Engine.$Args['Storage'].$Args['Dir'].$Args['DDL']['I'], $Args['DDL']['Data']);
}

function F_SubFS_Read ($Args)
{
    $Result = null;
    $FN = $Args['Storage'].$Args['Dir'].$Args['DDL']['I'];
    
    if (file_exists(Root.$FN))
        $Result = file_get_contents (Root.$FN);
    elseif (file_exists(Engine.'_Shared/'.$FN))
        $Result = file_get_contents (Engine.'_Shared/'.$FN);

    Log::Tap('FS');
    return $Result;
}

function F_SubFS_Update ($Args)
{
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Dir'].$Args['DDL']['I'], $Args['DDL']['Data']);
}

function F_SubFS_Delete ($Args)
{
    Log::Tap('FS');
    return unlink (Root.$Args['Storage'].$Args['Dir'].$Args['DDL']['I']);
}

function F_SubFS_Exist ($Args)
{
    $Result = false;
    
    if (!($Result = file_exists (Root.$Args['Storage'].$Args['Dir'].$Args['DDL']['I'])))
        $Result = file_exists (Engine.'_Shared/'.$Args['Storage'].$Args['Dir'].$Args['DDL']['I']);
    Log::Tap('FS');
    return $Result;
}