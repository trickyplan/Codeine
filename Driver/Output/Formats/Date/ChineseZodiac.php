<?php

    function F_ChineseZodiac_Format ($Date)
    {	
		$Chinese  = array('Monkey','Rooster','Dog','Pig','Rat','Ox','Tiger',
                                    'Rabbit','Dragon','Serpent','Horse','Goat');	
		return $Chinese[date('Y',$Date) % 12];
    }
    
    