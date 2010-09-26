<?php

function F_OOMySQLiND_Mount ($Storage)
{
    $Client   = 'set_username:set_password';
    $Server   = 'set_server';
    $Login    = 'set_login';
    $Password = 'set_password';
    $Host     = 'set_host';
    $Database = 'set_database';
    $Port     = '3306';

    if (isset($Storage['DSN']))
    {
        list ($Client, $Server) = explode('@', $Storage['DSN']);
        list ($Login, $Password) = explode(':', $Client);
        list ($Host, $Database) = explode('/', $Server);
        list ($Host, $Port) = explode(':',$Host);

        $Link = new mysqli($Host, $Login, $Password, $Database);

        if ($Link)
            $Link->set_charset('utf8');
    }
    else
        $Link = null;

    return $Link;
}

function F_OOMySQLiND_Unmount ($Storage)
{
    if (is_resource($Storage))
        return $Storage->close();
}

function F_OOMySQLiND_Create ($Args)
{
    $Fields = array();
    $Values = array();

    foreach ($Args['DDL'] as $Row)
    {
        $Values = array();
        foreach($Row as $Key => $Value)
        {
            $Fields[$Key] = '`'.$Args['Storage']->escape_string($Key).'`';
            $Values[$Key] = '\''.$Args['Storage']->escape_string($Value).'\'';
        }
        $ValueStr[] = '('.implode (',',$Values).')';
    }

    $Query = 'INSERT `'.$Args['Dir'].'` ('.implode (',',$Fields).') VALUES '.implode(',',$ValueStr);

    if ($Args['Storage']->query($Query))
        $Result = Log::Info($Query);
    else
        $Result = Log::Error($Query);
    
    Log::Tap('SQL');
    Log::Tap('SQL Insert');
    return $Result;
}

function F_OOMySQLiND_Read ($Args)
{
    $IC = 1;
    $Fields = array();

    if (isset($Args['DDL']['Where']))
        foreach($Args['DDL']['Where'] as $Where => $Vals)
        {
            $Condition = 'WHERE '.$Where;
            foreach($Vals as $Key => $Value)
            {
                if (empty($Value))
                    return null;

                $Condition = str_replace ('k'.$IC, '`'.$Args['Storage']->escape_string($Key).'`', $Condition);

                    if (!is_array ($Value))
                        $Condition = str_replace ('v'.$IC, '\''.$Args['Storage']->escape_string($Value).'\'', $Condition);
                    else
                        {
                            $Ins = array ();
                            foreach ($Value as $cValue)
                                $Ins[] = '\''.$Args['Storage']->escape_string($cValue).'\'';

                            $Condition = str_replace ('v'.$IC, implode (',',$Ins), $Condition);
                        }
                $IC++;
            }
        }
        else $Condition = '';

    if (is_array ($Args['DDL']['Fields']))
        {
            foreach($Args['DDL']['Fields'] as $Field)
                $Fields[] = '`'.$Field.'`';
            $FStr = implode(',',$Fields);
        }
    else
        $FStr = '*';

    if (isset($Args['DDL']['Sort']))
        {
            if (isset($Args['DDL']['Sort']['Key']))
                $OrderStr = ' ORDER BY `'.$Args['DDL']['Sort']['Key'].'`+0 ';
            if (isset($Args['DDL']['Sort']['Direction']))
                $OrderStr.= $Args['DDL']['Sort']['Direction'];
        }
    else
        $OrderStr = '';

    $Query = 'SELECT '.$FStr.' FROM `'.$Args['Dir'].'` '.$Condition.$OrderStr;

    Profiler::Go($Query);

    $Result = $Args['Storage']->query ($Query);

    Profiler::Stop($Query);

    $Data = null;

    if (!$Result)
        $Data = Log::Error($Query);
    else
        {
            $Data = $Result->fetch_all(MYSQLI_ASSOC);
            
            $Result->free();
            Log::Info('['.Profiler::Get($Query).']'.$Query);
            Profiler::Erase($Query);
        }   

    Log::Tap('SQL');
    Log::Tap('SQL Select');

    return $Data;
}

function F_OOMySQLiND_Update ($Args)
{  
    $IC = 1;
    $Mods = array();

    if (isset($Args['DDL']['Where']))
        foreach($Args['DDL']['Where'] as $Where => $Vals)
            {
                $Condition = ' WHERE '.$Where;
                foreach($Vals as $Key => $Value)
                {
                    $Condition = str_replace ('k'.$IC, '`'.$Args['Storage']->escape_string($Key).'`', $Condition);
                    $Condition = str_replace ('v'.$IC++, '\''.$Args['Storage']->escape_string($Value).'\'', $Condition);
                }
            }

    foreach($Args['DDL']['Data'] as $Key => $Value)
        $Mods[] = '`'.
            $Args['Storage']->escape_string($Key).'` = '.'\''.
            $Args['Storage']->escape_string($Value).'\'';

    $Query = 'UPDATE `'.$Args['Dir'].'` SET '.implode(',',$Mods).$Condition;
    
    if ($Args['Storage']->query ($Query))
        $Result = Log::Info($Query);
    else
        $Result = Log::Error($Query);

    Log::Tap('SQL');
    Log::Tap('SQL Update');
    
    return $Result;
}

function F_OOMySQLiND_Delete ($Args)
{
    $IC = 1;

    foreach($Args['DDL'] as $Where => $Vals)
    {
        $Condition = $Where;
        foreach($Vals as $Key => $Value)
        {
           $Condition = str_replace ('k'.$IC, '`'.$Args['Storage']->escape_string($Key).'`', $Condition);
           $Condition = str_replace ('v'.$IC++, '\''.$Args['Storage']->escape_string($Value).'\'', $Condition);
        }
    }

    $Query = 'DELETE FROM `'.$Args['Dir'].'` WHERE ('.$Condition.')';

    if ($Args['Storage']->query ($Query))
        $Result = Log::Info($Query, 3);
    else
        $Result = Log::Error($Query);

    Log::Tap('SQL');
    Log::Tap('SQL Delete');
    return $Result;
}