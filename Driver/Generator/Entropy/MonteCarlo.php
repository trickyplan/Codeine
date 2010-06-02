<?php
##################################################
# OSWA i Web Platform              			 #
# Платформа:  0.3.000 R102						 #
# © Macrox Labs, 2005 - 2008, http://labs.macrox.net 		 #
# Компонент: MonteCarlo							 #
# Версия:  0.3.000 R102						 #
# Тип: Драйвер энтропии						 	 #
# Описание: Метод Монте-Карло 					 #
# © Бездыханный, 2007, basileff@gmail.com		 #
##################################################

function F_MonteCarlo_Get($Args)
	{
		return mt_rand($Args["Min"],$Args["Max"]);
	};


?>