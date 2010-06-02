<?php

function F_Flatfile_Mount ($Args)
{
    return $Args['DSN'];
}

function F_Flatfile_Unmount ($Args)
{
    return true;
}

function F_Flatfile_Create ($Args)
{
    Log::Tap('FS');
    return file_put_contents ($Args['Dir'].'/'.$Args['DDL']['I'], $Args['DDL']['Data']);
}

function F_Flatfile_Read ($Args)
{
    $Data = explode("\n", file_get_contents ($Args['Dir'].'/'.$Args['DDL']['I']));
    foreach ($Data as $Key => $Value)
        $Data[$Key] = trim($Value);
    Log::Tap('FS');
    return $Data;
}

function F_Flatfile_Update ($Args)
{
    Log::Tap('FS');
    return file_put_contents ($Args['Dir'].'/'.$Args['DDL']['I'], $Args['DDL']['Data']);
}

function F_Flatfile_Delete ($Args)
{
    Log::Tap('FS');
    return unlink ($Args['Dir'].'/'.$Args['DDL']['I']);
}

function F_Flatfile_Exist ($Args)
{
    Log::Tap('FS');
    return file_exists($Args['Dir'].'/'.$Args['DDL']['I']);
}