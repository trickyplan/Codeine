<?php

  function F_gostH_Get($Args)
      {
	  return hash('gost', $Args);
      }