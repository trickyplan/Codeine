<?php

    function F_RSS2_Encode($Args)
    {
        $xml = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>'.$Args['Title'].'</title>
    <description>'.$Args['Description'].'</description>
    <language>ru-ru</language>
    <lastBuildDate>'.date(DATE_RSS).'</lastBuildDate>
    <generator>Codeine</generator>
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

    function F_RSS2_Decode($RSS)
    {
        $RSS = simplexml_load_string($RSS);
        $Imported = array();
        $ic = 0;

            foreach($RSS->channel->item as $Item)
            {
                $ic++;
                $Imported[$ic] = array();
                $Imported[$ic]['GUID']        = (string) $Item->guid;
                $Imported[$ic]['Link']        = (string) $Item->link;
                $Imported[$ic]['Title']       = (string) $Item->title;
                $Imported[$ic]['Description'] = (string) $Item->description;
                $Imported[$ic]['Category']    = (string) $Item->category;
                $Imported[$ic]['Author']      = (string) $Item->author;
                $ds = date_parse_from_format('D, d M Y H:i:s', (string) $Item->pubDate);
                $Imported[$ic]['CreatedOn']   = mktime ($ds['hour'], $ds['minute'], $ds['second'], $ds['month'], $ds['day'], $ds['year']);
            }
            
        return $Imported;
    }