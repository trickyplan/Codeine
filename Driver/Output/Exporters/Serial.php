<?php
##################################################
# OSWA i Web Platform              			 #
# Платформа:  0.3.000 R102					 	 #
# © Macrox Labs, 2005 - 2008, http://labs.macrox.net 		 #
# Компонент: Serial Exporter					 #
# Версия:  0.1.100 R95						 #
# Тип: Драйвер  						 		 #
# Описание: Экспортер формата PHP Serialize      #
# © Бездыханный, 2007, basileff@gmail.com		 #
##################################################

function F_Serial_Export($Args)
{
    return serialize($Args["Imported"]);
};

?>