<?php

function F_Memcached_Mount ($Args)
{
    $Host = 'localhost';
    $Port = '11211';

    list ($Host, $Port) = explode (':', $Args['DSN']);

    $Memcached = new Memcached();
    if (!$Memcached->addServer($Host, $Port))
    {
        Log::Error('Data:Memcached: Cannot connect to '.$Args['DSN']);
        return null;
    }

    return $Memcached;
}

function F_Memcached_Unmount ($Args)
{
    return true;
}

function F_Memcached_Create ($Args)
{
    if ($Args['Storage'])
        return $Args['Storage']->add($Args['Dir'].$Args['DDL']['I'], $Args['DDL'], 3600);
}

function F_Memcached_Read ($Args)
{
    if ($Args['Storage'])
        return $Args['Storage']->get($Args['Dir'].$Args['DDL']['I']);
}

function F_Memcached_Update ($Args)
{
    if ($Args['Storage'])
        return $Args['Storage']->set($Args['Dir'].$Args['DDL']['I'], $Args['DDL']);
}

function F_Memcached_Delete ($Args)
{
    if ($Args['Storage'])
        return $Args['Storage']->delete($Args['Dir'].$Args['DDL']['I']);
}