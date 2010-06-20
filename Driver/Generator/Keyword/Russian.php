<?php

function F_Russian_Generate($Args)
{
	$Set = 'АБВГДЕЖЗИКЛМНОПРСТУФХЧШЫЭЮЯ';
        $length = $Args['Length'];
	
	for ($a = 0; $a<$length; $a++)
            $Password.= $Set[array_rand($Set)];
	
	return $Password;
}
