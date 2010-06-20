<?php
##################################################
# OSWA i Web Platform              			 #
# Платформа:  0.3.000 R102					 	 #
# © Macrox Labs, 2005 - 2008, http://labs.macrox.net 		 #
# Компонент: RSS 2.0 Exporter					 #
# Версия:  0.3.000 R102						 #
# Тип: Драйвер  						 		 #
# Описание: Драйвер экспорта XML             #
# © Бездыханный, 2007, basileff@gmail.com		 #
##################################################

# Инклюдинг
# Детерминация

# Инициализация

# Исполнение

function F_XML_Export($Args)
{
    $xml = simplexml_load_string('<?xml version="1.0"?>
<export>
  <header>
    <title>'.$Args["Title"].'</title>
    <description>'.$Args["Description"].'</description>
    <lastBuildDate>'.date("r").'</lastBuildDate>
    <generator>i Web Platform '._Version.'</generator>
    <link>'._Host.'</link>
	<total>'.count($Args["Imported"]).'</total>
   </header>
   <data>
   </data>
</export>');
    //
    $ic = 0;

    $channel = $xml->data;
	if (is_numeric($Args["Start"])) $Args["Imported"] = array_splice($Args["Imported"], $Args["Start"], $Args["Length"]);
    foreach ($Args["Imported"] as $Imported)
    {
        $ic++;
		$Imported->Reload();
        $item = $channel->addChild('item','');
		$item->addChild('ID',$Imported->Name);
        foreach ($Imported->Data as $Key => $Value) $item->addChild($Key, quotemeta(implode(" ",$Value)));
    };
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