<?php

  function F_salsa20H_Get($Args)
      {
	  return hash('salsa20', $Args);
      }