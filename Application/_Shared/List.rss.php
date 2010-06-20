<?php

$Object = new Object(self::$Name);
$Model = $Object->Model();

if (!self::$Collection->Queried)
    self::$Collection->Query(self::$ID);

self::$Collection->Sort('CreatedOn', 'DESC');
self::$Collection->Page(1);

self::$Collection->Load();

$Imported = array();

$RSS = array();

foreach ($Model->Nodes as $Name => $Node)
    if (isset($Node->RSS))
        $RSS[$Node->RSS] = $Name;

foreach (self::$Collection->_Items as $ID => $Object)
    {
        foreach ($RSS as $RNode => $ONode)
            $Imported[$ID][$RNode] = $Object->Get($ONode);
        
        $Imported[$ID]['author']  = $Object->Get('Face');
        $Imported[$ID]['pubDate'] = date(DATE_RSS, $Object->Get('CreatedOn'));
        $Imported[$ID]['guid']    = Host.$Object->Name;
        $Imported[$ID]['link']    = Host.self::$Name.'/~'.$Object->Name;

        if (!isset($Imported[$ID]['title']))
            $Imported[$ID]['title'] = $Imported[$ID]['link'];
    }

Page::Body(Code::E('Output/Exporters','Export', array('Title'=>_Host,'Description'=>'<l>RSS:Desc:'.self::$Name.'</l>', 'Imported'=>$Imported), 'RSS2'));