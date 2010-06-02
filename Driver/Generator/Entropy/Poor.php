<?php
##################################################
# OSWA i Web Platform              			 #
# Платформа:  0.3.000 R102						 #
# © Macrox Labs, 2005 - 2008, http://labs.macrox.net 		 #
# Компонент: Poor								 #
# Версия:  0.3.000 R102						 #
# Тип: Драйвер энтропии						 	 #
# Описание: Тестовый плохой алгоритм 			 #
# © Бездыханный, 2007, basileff@gmail.com		 #
##################################################

function F_Poor_Get($Args)
	{
		return rand($Args["Min"]+rand($Args["Max"],$Args["Max"]*5));
	}