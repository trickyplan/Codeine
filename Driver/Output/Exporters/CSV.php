<?php
##################################################
# OSWA i Web Platform              			 #
# Платформа:  0.3.000 R102					 	 #
# © Macrox Labs, 2005 - 2008, http://labs.macrox.net 		 #
# Компонент: CSV Exporter						 #
# Версия:  0.1.100 R95						 #
# Тип: Драйвер  						 		 #
# Описание: Экспортер формата CSV            	 #
# © Бездыханный, 2007, basileff@gmail.com		 #
##################################################

function F_CSV_Export($Args)
{
    $CVS = "";
    foreach ($Args["Imported"] as $Imported)
    {
        $Imported->Reload();
        $CSV.="$Imported->Type: $Imported->Name ";
        foreach ($Imported->Data as $Key => $Value)
            $CSV.=",$Key=$Value";
        $CSV.=";";
    };

    return $CSV;
}

?>