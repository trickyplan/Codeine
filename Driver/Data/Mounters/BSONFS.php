<?php

function F_BSONFS_Mount ($Args)
{
    if (extension_loaded('mongo'))
        return $Args['DSN'];
    else
        return false;
}

function F_BSONFS_Unmount ($Args)
{
    return true;
}

function F_BSONFS_Create ($Args)
{
    if (!is_dir(Root.$Args['Storage'].'/'.$Args['Dir'].'/'))
        mkdir(Root.$Args['Storage'].'/'.$Args['Dir'].'/', '0777', true);
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Point'].'/'.$Args['DDL']['I'], bson_encode ($Args['DDL']['Data']));
}

function F_BSONFS_Read ($Args)
{
    Log::Tap('FS');
    return bson_decode (file_get_contents (Root.$Args['Storage'].$Args['Point'].'/'.$Args['DDL']['I']));
}

function F_BSONFS_Update ($Args)
{
    Log::Tap('FS');
    return file_put_contents (Root.$Args['Storage'].$Args['Point'].'/'.$Args['DDL']['I'], bson_encode ($Args['DDL']['Data']));
}

function F_BSONFS_Delete ($Args)
{
    Log::Tap('FS');
    return unlink (Root.$Args['Storage'].$Args['Point'].'/'.$Args['DDL']['I']);
}

function F_BSONFS_Exist ($Args)
{
    Log::Tap('FS');
    return file_exists (Root.$Args['Storage'].$Args['Point'].'/'.$Args['DDL']['I']);
}