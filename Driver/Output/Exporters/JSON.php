<?php

function F_JSON_Encode($Args)
{
  if (null === $Args) return 'null';
  if ($Args === false) return 'false';
  if ($Args === true) return 'true';
  if (is_scalar($Args))
  {
    if (is_float($Args))
    {
      // Always use "." for floats.
      $Args = str_replace(',', '.', strval($Args));
    }

    // All scalars are converted to strings to avoid indeterminism.
    // PHP's "1"&&1 are equal for all PHP operators, but
    // JS's "1"&&1 are not. So if we pass "1" or 1 from the PHP backend,
    // we should get the same result in the JS frontend (string).
    // Character replacements for JSON.
    static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
    array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
    return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $Args) . '"';
  }
  $isList = true;
  for ($i = 0, reset($Args); $i < count($Args); $i++, next($Args))
  {
    if (key($Args) !== $i)
    {
      $isList = false;
      break;
    }
  }
  $Result = array();
  if ($isList)
  {
    foreach($Args as $Value)
        $Result[] = F_JSON_Encode($Value);
    
    return '[ ' . implode(', ', $Result) . ' ]';
  }
  else
  {
    foreach($Args as $Key => $Value)
        $Result[] = F_JSON_Encode($Key).': '.F_JSON_Encode($Value);
    return '{ ' . implode(', ', $Result) . ' }';
  }
}