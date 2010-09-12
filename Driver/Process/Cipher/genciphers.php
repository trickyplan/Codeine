<?php

$algos = mcrypt_list_algorithms();
$modes = mcrypt_list_modes();

foreach ($algos as $algo)
{
    $algo2 = strtoupper(str_replace('-','_',$algo));
    $fn = strtoupper(str_replace('-','',$algo));

    $Str .= '<?php';

    foreach ($modes as $mode)
    {
	$mode = strtoupper($mode);
	$Str .= '
	  function F_'.$fn.'_Encrypt'.$mode.' ($Args)
	    {
		return mcrypt_encrypt(MCRYPT_'.$algo2.', $key, $text, MCRYPT_MODE_'.$mode.')
	    }

	  function F_'.$fn.'_Decrypt'.$mode.' ($Args)
	    {
		return mcrypt_decrypt(MCRYPT_'.$algo2.', $key, $text, MCRYPT_MODE_'.$mode.')
	    }';
    }

    file_put_contents($fn.'.php',$Str); $Str ='';
}
