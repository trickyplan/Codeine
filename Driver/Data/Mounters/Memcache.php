<?php

function F_Memcache_Mount ($Args)
{
    $Host = 'localhost';
    $Port = '11211';

    list ($Host, $Port) = explode (':', $Args['DSN']);

    $Memcached = new Memcache();
    if (!$Memcached->connect($Host, $Port))
    {
        Log::Error('Data:Memcached: Cannot connect to '.$Args['DSN']);
        return false;
    }

    return $Memcached;
}

function F_Memcache_Unmount ($Args)
{
    if ($Args)
        return $Args->close();
}

function F_Memcache_Create ($Args)
{
    if ($Args['Storage'])
        return $Args['Storage']->add($Args['Dir'].$Args['DDL']['I'], $Args['DDL'], false, 3600);
}

function F_Memcache_Read ($Args)
{
    if ($Args['Storage'])
        return $Args['Storage']->get($Args['Dir'].$Args['DDL']['I']);
}

function F_Memcache_Update ($Args)
{
    if ($Args['Storage'])
        return $Args['Storage']->set($Args['Dir'].$Args['DDL']['I'], $Args['DDL']);
}

function F_Memcache_Delete ($Args)
{
    if ($Args['Storage'])
        return $Args['Storage']->delete($Args['Dir'].$Args['DDL']['I']);
}