<?php
##################################################
# OSWA i Web Platform              			 #
# Платформа:  0.3.000 R102					 	 #
# © Macrox Labs, 2005 - 2008, http://labs.macrox.net 		 #
# Компонент: Serial Importer					 #
# Версия:  0.1.100 R95						 #
# Тип: Драйвер  						 		 #
# Описание: Импортер формата PHP Serialize     	 #
# © Бездыханный, 2007, basileff@gmail.com		 #
##################################################

function F_Serial_Import($Args)
{
    return unserialize($Args["String"]);
}

?>