<?php
##################################################
# OSWA i Web Platform              			 #
# Платформа:  0.3.000 R102						 #
# © Macrox Labs, 2005 - 2008, http://labs.macrox.net 		 #
# Компонент: vx10st								 #
# Версия:  0.3.000 R102						 #
# Тип: Драйвер энтропии						 	 #
# Описание: Алгоритм vx10				 		 #
# © Бездыханный, 2007, basileff@gmail.com		 #
##################################################

function F_vx10st_Get($Args)
	{
			global $SecurityAI;
			do
			{
				$GammaCount=rand(1,$SecurityAI->GetValueOf("10 Gamma Maximal Count",true));
				$GammaLevel=rand($Args["Min"],$Args["Max"]);
				$CubeSize=$SecurityAI->GetValueOf("10 Cube Size",true);
				for($x=1;$x<=$CubeSize;$x++)
					for($y=1;$y<=$CubeSize;$y++)
						for($z=1;$z<=$CubeSize;$z++)
						{
							$WORK[$x][$y][$z]=rand($Args["Min"],$Args["Max"]);
						};
				for($g=1;$g<=$GammaCount;$g++)
				{
					$GammaS[$g]=rand(0,$GammaLevel);
					for($mx=1;$mx<=round($CubeSize);$mx++)
					for($my=1;$my<=round($CubeSize);$my++)
					for($mz=1;$mz<=round($CubeSize);$mz++)
					{
						$WORK[$mx][$my][$mz]+=rand(0,$GammaS[$g]);
						if ($WORK[$mx][$my][$mz]>$Args["Max"]) $WORK[$mx][$my][$mz]=$WORK[$mx][$my][$mz]%$Args["Max"];
						if ($WORK[$mx][$my][$mz]<$Args["Min"]) $WORK[$mx][$my][$mz]=$WORK[$mx][$my][$mz]%$Args["Min"];
					};
				};
				$tx=rand(1,$CubeSize);
				$ty=rand(1,$CubeSize);
				$tz=rand(1,$CubeSize);
			}
			while (!($WORK[$tx][$ty][$tz]>=$Args["Min"])and($WORK[$tx][$ty][$tz]<=$Args["Max"]));
			return $WORK[$tx][$ty][$tz];
	};



?>