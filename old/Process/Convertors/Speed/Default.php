<?php

  function F_Speed_Convert ($Args)
  {
	$UINM = array(
	'mile' => 1609.4,
	'km' => 1000,
	'm' => 1,
	'dm' => 0.1,
	'sm' => 0.01,
	'mm' => 0.001,
	'inch' => 0.025,
	'feet' => 0.3048,
	'yard' => 0.9144,
	'kabeltov' => 185.2,
	'nautical mile' => 1852,
	'light second' => 300000000,
	'light minute' => 18000000000,
	'light hour' => 1080000000000,
	'light day' => 25920000000000,
	'light year' => 9460800000000000
	);
	// Units In Seconds. Количество секунд, в единице.
	$UINS = array(
	'hour' => 3600,
	'minute' => 60,
	'second' => 1,
	'day' => 86400,
	'week' => 604800,
	'year' => 31536000,
	);

	list($InS, $InT) = explode('/',$Args['From']);
	list($OutS, $OutT) = explode('/',$Args['To']);

	$Output = $Args['Input']*($UINM[$InS]/$UINS[$InT])*($UINS[$OutT]/$UINM[$OutS]);

	return $Output;
  }