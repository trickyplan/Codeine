<?php
##################################################
# OSWA i Web Platform              			 #
# Платформа:  0.3.000 R102						 #
# © Macrox Labs, 2005 - 2008, http://labs.macrox.net 		 #
# Компонент: DD Standart						 #
# Версия:  0.3.000 R102						 #
# Тип: Драйвер энтропии						 	 #
# Описание: Рассекающий стандартный алгоритм 	 #
# © Бездыханный, 2007, basileff@gmail.com		 #
##################################################

function F_DDStandart_Get($Args)
	{
		return rand($Args["Min"],($Args["Max"]-$Args["Min"])/2)+rand(($Args["Max"]-$Args["Min"])/2,$Args["Max"]);
	}
