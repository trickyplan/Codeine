<?php

function F_RussianRoulette_Check($Args)
{
    $RR = mt_rand(0,6);
	return $RR == 6 ? true:false;
}