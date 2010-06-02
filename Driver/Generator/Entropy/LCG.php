<?php

function F_LCG_Get($Args)
	{
		return ($Args["Min"]+round(lcg_value()*($Args["Max"]-$Args["Min"])));
	}
