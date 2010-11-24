<?php

function F_Redis_Mount ($Args)
{
    $Server = '127.0.0.1';
    $Port = '6379';
    
    if (!$Args['DSN'])
      $Args['DSN'] = '127.0.0.1:6379';

    list ($Server, $Port) = explode(':',$Args['DSN']);

    $Redis = new Redis();

    if ($Redis->connect($Server, $Port) === false)
        return Log::Error('Connect to Redis failed');
    else
        return $Redis;
}

function F_Redis_Unmount ($Args)
{
    if ($Args)
        $Args->close();
}

function F_Redis_Create ($Args)
{
    if ($Args['Storage'])
        return $Args['Storage']->set($Args['Dir'].$Args['DDL']['I'], json_encode($Args['DDL']));
}


function F_Redis_Read ($Args)
{
    if ($Args['Storage'])
        return json_decode($Args['Storage']->get($Args['Dir'].$Args['DDL']['I']), true);
}

function F_Redis_Update ($Args)
{
    if ($Args['Storage'])
       return $Args['Storage']->set($Args['Dir'].$Args['DDL']['I'], json_encode($Args['DDL']));
}

function F_Redis_Delete ($Args)
{
    if ($Args['Storage'])
      return $Args['Storage']->delete($Args['Dir'].$Args['DDL']['I']);
}
