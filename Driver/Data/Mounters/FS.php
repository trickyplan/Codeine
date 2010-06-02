<?php

function F_FS_Mount ($Args)
{
    return $Args['DSN'];
}

function F_FS_Unmount ($Args)
{
    return true;
}

function F_FS_Create ($Args)
{
    if (!is_dir(Root.$Args['Storage'].'/'.$Args['Dir'].'/'))
        mkdir(Root.$Args['Storage'].'/'.$Args['Dir'].'/', '0777', true);
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].'/'.$Args['Dir'].'/'.$Args['DDL']['I'], $Args['DDL']['Data']);
}

function F_FS_Read ($Args)
{
    Log::Tap('FS');
    return file_get_contents (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I']);
}

function F_FS_Update ($Args)
{
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].'/'.$Args['Dir'].'/'.$Args['DDL']['I'], $Args['DDL']['Data']);
}

function F_FS_Delete ($Args)
{
    Log::Tap('FS');
    return unlink (Root.$Args['Storage'].'/'.$Args['Dir'].'/'.$Args['DDL']['I']);
}

function F_FS_Exist ($Args)
{
    Log::Tap('FS');
    return file_exists(Root.$Args['Storage'].'/'.$Args['Dir'].'/'.$Args['DDL']['I']);
}