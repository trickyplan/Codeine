<?php

  $Timings = array();
  
  for ($a=1; $a<100; $a++)
    $Timings[$a] = abs(microtime(true)-microtime(true));

  $Results['Timer resolution'] = round(1/(array_sum($Timings)/$a));
  
  $Results['Memory limit'] = ini_get('memory_limit');
  exec('free -m | grep Mem', $Memory);
  exec('uptime | grep load', $Uptime);

  preg_match_all('/([\d]+\.[\d]+)/',$Uptime[0], $Uptimes); 

  /*
   *  TODO Top parser 
   *  
   *  Cpu(s): 15.6%us,  5.5%sy,  0.0%ni, 78.0%id,  0.0%wa,  0.9%hi,  0.0%si,  0.0%st
   * 
   * TODO BogoMIPS
   *  
   * TODO CPUInfo
   */

  preg_match_all('/([\d]+)/',$Memory[0], $Free);
  list (
          $Results['Memory']['Total'],
          $Results['Memory']['Used'],
          $Results['Memory']['Free'],
          $Results['Memory']['Shared'],
          $Results['Memory']['Buffers'],
          $Results['Memory']['Cached']
      ) = $Free[1];

  list ($Results['LoadAverage']['M1'], $Results['LoadAverage']['M10'], $Results['LoadAverage']['M15']) = $Uptimes[1];

  View::Body(json_encode($Results));