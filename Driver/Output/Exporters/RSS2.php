<?php

function F_RSS2_Export($Args)
{
    $xml = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>'.$Args['Title'].'</title>
    <description>'.$Args['Description'].'</description>
    <language>ru-ru</language>
    <lastBuildDate>'.date(DATE_RSS).'</lastBuildDate>
    <generator>i Web Platform </generator>
    <link>'.Host.'</link>
   </channel>
</rss>');
    //
    $ic = 0;
    $channel = $xml->channel;

    foreach ($Args['Imported'] as $Imported)
    {
        $ic++;
        $item = $channel->addChild('item','');
        foreach ($Imported as $Key => $Value)
            $item->addChild($Key, $Value);

    }

    return $xml->asXML();
}
# Терминация

#####################################################
#	Заметки:										#
#													#
#													#
#													#
#####################################################

?>