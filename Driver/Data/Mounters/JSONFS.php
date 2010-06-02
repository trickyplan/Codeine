<?php

function F_JSONFS_Mount ($Args)
{
    return $Args['DSN'];
}

function F_JSONFS_Unmount ($Args)
{
    return true;
}

function F_JSONFS_Create ($Args)
{
    if (!is_dir(Root.$Args['Storage'].'/'.$Args['Dir'].'/'))
        mkdir(Root.$Args['Storage'].'/'.$Args['Dir'].'/', '0777', true);
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Point'].'/'.$Args['DDL']['I'], json_encode ($Args['DDL']['Data']));
}

function F_JSONFS_Read ($Args)
{
    Log::Tap('FS');
    return json_decode (file_get_contents (Root.$Args['Storage'].$Args['Point'].'/'.$Args['DDL']['I']));
}

function F_JSONFS_Update ($Args)
{
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Point'].'/'.$Args['DDL']['I'], json_encode ($Args['DDL']['Data']));
}

function F_JSONFS_Delete ($Args)
{
    Log::Tap('FS');
    return unlink (Root.$Args['Storage'].$Args['Point'].'/'.$Args['DDL']['I']);
}

function F_JSONFS_Exist ($Args)
{
    Log::Tap('FS');
    return file_exists (Root.$Args['Storage'].$Args['Point'].'/'.$Args['DDL']['I']);
}