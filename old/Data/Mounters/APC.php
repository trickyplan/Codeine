<?php

function F_APC_Mount ($Args)
{
    return extension_loaded('apc');
}

function F_APC_Unmount ($Args)
{
    return true;
}

function F_APC_Create ($Args)
{
    if (!apc_add ($Args['Dir'].$Args['DDL']['I'], $Args['DDL']))
        Log::Error('APC Add');
    else
        return true;
}

function F_APC_Read ($Args)
{
    if (!($Data = apc_fetch($Args['Dir'].$Args['DDL']['I'])))
        $Data = null;
    return $Data;
}

function F_APC_Update ($Args)
{
    if (!apc_store($Args['Dir'].$Args['DDL']['I'], $Args['DDL']))
        return Log::Warning('Сохранение в APC не удалось');
    else
        return true;
}

function F_APC_Delete ($Args)
{
    return apc_delete ($Args['Dir'].$Args['DDL']['I']);
}

function F_APC_Exist ($Args)
{
    if (null != apc_fetch($Args['Dir'].$Args['DDL']['I']))
        return true;
    else
        return false;
}