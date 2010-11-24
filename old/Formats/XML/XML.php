<?php

    function F_XML_Encode($Args)
    {
        $xml = simplexml_load_string('<?xml version="1.0"?>
<export>
  <header>
    <title>'.$Args['Title'].'</title>
    <description>'.$Args['Description'].'</description>
    <lastBuildDate>'.date('r').'</lastBuildDate>
    <generator>i Web Platform '._Version.'</generator>
    <link>'._Host.'</link>
        <total>'.count($Args['Imported']).'</total>
   </header>
   <data>
   </data>
</export>');

        $channel = $xml->data;
            if (is_numeric($Args['Start']))
                $Args['Imported'] = array_splice($Args['Imported'], $Args['Start'], $Args['Length']);
            
        foreach ($Args['Imported'] as $Imported)
        {
            $Imported->Reload();
            $item = $channel->addChild('item','');
            $item->addChild('ID',$Imported->Name);
            foreach ($Imported->Data as $Key => $Value)
                $item->addChild($Key, quotemeta(implode(" ",$Value)));
        }
        
        return $xml->asXML();
    }

    function F_XML_Decode($Args)
    {
        return simplexml_load_string($Args);
    }