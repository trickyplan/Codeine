<?php

  function F_Log_Profile($Args)
  {
        foreach($Args as $K => $C)
            Log::Perfomance ($C['S'].': '.round($C['T']*1000,2).' ms ('.$C['C'].')');
  }