<?php

  function F_salsa10H_Get($Args)
      {
	  return hash('salsa10', $Args);
      }