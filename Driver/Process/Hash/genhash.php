<?php

$algos = hash_algos();
foreach ($algos as $algo)
{
    $algo2 = str_replace(',','_',$algo);
    file_put_contents($algo2.'.php','<?php

  function F_'.$algo2.'H_Get($Args)
      {
	  return hash(\''.$algo.'\', $Args);
      }');
}
