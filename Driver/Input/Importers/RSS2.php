<?php
##################################################
# OSWA i Web Platform              			 #
# Платформа:  0.3.000 R102					 	 #
# © Macrox Labs, 2005 - 2008, http://labs.macrox.net 		 #
# Компонент: RSS 2.0 Importer					 #
# Версия:  0.3.000 R102						 #
# Тип: Драйвер  						 		 #
# Описание: Драйвер импорта RSS 2.0              #
# © Бездыханный, 2007, basileff@gmail.com		 #
##################################################

# Инклюдинг
# Детерминация

# Инициализация

# Исполнение

function F_RSS2_Import($Args)
{
		$XML = simplexml_load_file($Args["URL"]);
        $Imported = array();
		$ic = 0;
        foreach($XML->channel->item as $Item)
        {
            $ic++;
            $Imported[$ic]->Date = $Item->pubDate;
            $Imported[$ic]->GUID = $Item->guid;
            $Imported[$ic]->Link = $Item->link;
            $Imported[$ic]->Title = $Item->title;
            $Imported[$ic]->Description = $Item->description;
            $Imported[$ic]->Category = $Item->category;
            $Imported[$ic]->Author = $Item->author;
        };
	    return $Imported;
}

# Терминация

#####################################################
#	Заметки:										#
#													#
#													#
#													#
#####################################################

?>