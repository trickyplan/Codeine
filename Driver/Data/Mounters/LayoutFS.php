<?php

function F_LayoutFS_Mount ($Args)
{
    return $Args['DSN'];
}

function F_LayoutFS_Unmount ($Args)
{
    return true;
}

function F_LayoutFS_Create ($Args)
{
    return 'Unrealized';
}

function F_LayoutFS_Read ($Args)
{
    if (!is_array ($Args['DDL']['I']))
        $IDs = array ($Args['DDL']['I']);
    else
        $IDs = $Args['DDL']['I'];

    $Layout = '';
    $Candidates = array();

    $SZC = sizeof($IDs);

    $IC = 0;

    if (Application::$Interface == 'web')
        for ($a = 0; $a<$SZC; $a++)
            {
                $Candidates[$IC++] = Root.$Args['Dir'].DIRSEP.$IDs[$a].'.html';
                $Candidates[$IC++] = EngineShared.$Args['Dir'].DIRSEP.$IDs[$a].'.html';
            }
    else
        for ($a = 0; $a<$SZC; $a++)
            {
                $Candidates[$IC++] = Root.$Args['Dir'].DIRSEP.$IDs[$a].'.'.Application::$Interface.'.html';
                $Candidates[$IC++] = Root.$Args['Dir'].DIRSEP.$IDs[$a].'.html';
                $Candidates[$IC++] = EngineShared.$Args['Dir'].DIRSEP.$IDs[$a].'.'.Application::$Interface.'.html';
                $Candidates[$IC++] = EngineShared.$Args['Dir'].DIRSEP.$IDs[$a].'.html';
            }

    for ($a = 0; $a<$IC; $a++)
    {
        Log::Tap('FS');
        if (file_exists($Candidates[$a]))
            return file_get_contents($Candidates[$a]);
    }

    return '<content/>';
}

function F_LayoutFS_Update ($Args)
{
    return 'Unrealized';
}

function F_LayoutFS_Delete ($Args)
{
    return 'Unrealized';
}

function F_LayoutFS_Exist ($Args)
{
    return 'Unrealized';
}