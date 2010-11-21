<?php

function F_MongoDB_Mount ($Args)
{
    list ($Server, $Database) = explode('/',$Args['DSN']);
    $M  = new Mongo($Server);
    $DB = $M->$Database;
    return $DB;
}

function F_MongoDB_Unmount ($Args)
{
    return true;
}

function F_MongoDB_Create ($Args)
{
    if (!$Args['Storage']->$Args['Dir']->insert($Args['DDL']))
        return Log::Warning('Добавление в Mongo не удалось');
}

function F_MongoDB_Read ($Args)
{
    $Data = array();
    $Cursor = $Args['Storage']->$Args['Dir']->find($Args['DDL']);
    $IC = 0;

    while ($Cursor->hasNext())
    {
        $Data[$IC] = $Cursor->getNext();
        unset($Data[$IC]['_id']);
        $IC++;
    }

    if (empty($Data))
        $Data = null;
    elseif (count($Data)==1)
            $Data = $Data[0];
    
    return $Data;
}

function F_MongoDB_Update ($Args)
{
    $Args['Storage']->$Args['Dir']->remove(array('I'=>$Args['DDL']['I']));
    if (!$Args['Storage']->$Args['Dir']->insert($Args['DDL']))
        return Log::Warning('Обновление в Mongo не удалось');
    else
        return true;
}

function F_MongoDB_Delete ($Args)
{
    if (!$Args['Storage']->$Args['Dir']->remove($Args['DDL']))
        return Log::Warning('Удаление из Mongo не удалось');
    else
        return true;
}

function F_MongoDB_Exist ($Args)
{
    return true;
}