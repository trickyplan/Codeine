<?php

function F_Strong_Generate($Args)
{
        $length = $Args['Length'];
	
	for ($a = 0; $a<$length; $a++)
            $password.= chr(rand(48, 90));
	
	return $password;
}
