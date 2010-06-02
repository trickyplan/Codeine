<?php

function F_English_Generate($Args)
{
    $Password = '';
    $length = $Args['Length'];
	
	for ($a = 0; $a < $length; $a++)
            $Password.= chr(rand(65, 90));
	
	return $Password;
}
