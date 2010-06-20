<?php

function F_XMLFS_Mount ($Args)
{
    return $Args['DSN'];
}

function F_XMLFS_Unmount ($Args)
{
    return true;
}

function F_XMLFS_Create ($Args)
{
    if (!is_dir(Root.$Args['Storage'].'/'.$Args['Dir'].'/'))
        mkdir(Root.$Args['Storage'].'/'.$Args['Dir'].'/', '0777', true);
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.xml', $Args['DDL']['Data']);
}

function F_XMLFS_Read ($Args)
{
    Log::Tap('FS');
    return simplexml_load_string (file_get_contents (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.xml'));
}

function F_XMLFS_Update ($Args)
{
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.xml', $Args['DDL']['Data']);
}

function F_XMLFS_Delete ($Args)
{
    Log::Tap('FS');
    return unlink (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.xml');
}

function F_XMLFS_Exist ($Args)
{
    Log::Tap('FS');
    return file_exists (Root.$Args['Storage'].$Args['Dir'].'/'.$Args['DDL']['I'].'.xml');
}

