<?php

function F_ModelFS_Mount ($Args)
{
    return $Args['DSN'];
}

function F_ModelFS_Unmount ($Args)
{
    return true;
}

function F_ModelFS_Create ($Args)
{
    return 'Unrealized';
}

function F_ModelFS_Read ($Args)
{
    $Result = null;
    
    $FN = $Args['Dir'].'/'.$Args['DDL']['I'].'.json';
    Log::Tap('FS');

    if (file_exists(Root.$FN))
        $Result = file_get_contents (Root.$FN);
    elseif (file_exists(Engine.'_Shared/'.$FN))
        $Result = file_get_contents (Engine.'_Shared/'.$FN);
    else
        return Log::Error('SubJSONFS: File '.$FN.' Not Found');

    
    $Result = json_decode($Result);

    $Facets = array();
    
    if (isset($Args['DDL']['Facets']))
        $Facets = array_merge($Facets, $Args['DDL']['Facets']);
    
    if (isset($Result->Facets))
        $Facets = array_merge($Facets, $Result->Facets);

    if (!empty($Facets))
    {
        foreach($Facets as $Facet)
        {
            $Facet = Data::Read ('Model','{"I":"Facets/'.$Facet.'"}');
            $Result->Nodes = (object) array_merge( (array) $Result->Nodes, (array) $Facet->Nodes);
        }
    }

    foreach ($Result->Nodes as $Name => $Node)
        if ($Node->Type == 'Calculated')
            $this->_Calculated[$Name] = $Name;

    return $Result;
}

function F_ModelFS_Update ($Args)
{
    return 'Unrealized';
}

function F_ModelFS_Delete ($Args)
{
    return 'Unrealized';
}

function F_ModelFS_Exist ($Args)
{
    return 'Unrealized';
}