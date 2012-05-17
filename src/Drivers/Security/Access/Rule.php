<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    function array_diff_assoc_recursive($array1, $array2)
    {
        foreach($array1 as $key => $value)
        {
            if(is_array($value))
            {
                  if(!isset($array2[$key]))
                  {
                      $difference[$key] = $value;
                  }
                  elseif(!is_array($array2[$key]))
                  {
                      $difference[$key] = $value;
                  }
                  else
                  {
                      $new_diff = array_diff_assoc_recursive($value, $array2[$key]);
                      if($new_diff != FALSE)
                      {
                            $difference[$key] = $new_diff;
                      }
                  }
              }
              elseif(!isset($array2[$key]) || $array2[$key] != $value)
              {
                  $difference[$key] = $value;
              }
        }
        return !isset($difference) ? null : $difference;
    }

    self::setFn('Check', function ($Call)
    {
        foreach ($Call['Rules'] as $Name => $Rule)
        {
            if ((array_diff_assoc_recursive($Rule['Call'], $Call) == null)
                && ($Rule['Weight'] >
                    $Call['Weight']))
            {
                F::Log('Rule «'.$Name.'» applied');
                $Call['Decision'] = $Rule['Decision'];
                $Call['Weight'] = $Rule['Weight'];
            }
        }

        return $Call;
     });